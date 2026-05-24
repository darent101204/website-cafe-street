<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StandardizeProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:standardize-images {--dry-run : Preview standardization changes without modifying the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely standardizes all product image database paths to products/filename.png';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info("=========================================");
            $this->info("   DRY RUN MODE: PREVIEWING MIGRATION    ");
            $this->info("=========================================");
        } else {
            $this->warn("=========================================");
            $this->warn("    RUNNING ACTUAL PATH STANDARDIZATION   ");
            $this->warn("=========================================");
        }

        $products = Product::all();

        $total = $products->count();
        $alreadyStandard = 0;
        $legacyDetected = 0;
        $updated = 0;
        $skipped = 0;

        $updatesQueue = [];

        foreach ($products as $product) {
            $image = $product->image;

            if (empty($image)) {
                $this->line("Product #{$product->id} ('{$product->name}'): No image assigned. Skipping.");
                $alreadyStandard++;
                continue;
            }

            // Check if already in products/filename.ext format
            if (str_starts_with($image, 'products/')) {
                $alreadyStandard++;
                continue;
            }

            $legacyDetected++;
            $filename = basename($image);
            $newPath = 'products/' . $filename;

            // Verify file existence in storage/app/public/products/
            if (Storage::disk('public')->exists($newPath)) {
                $updatesQueue[] = [
                    'product' => $product,
                    'old_path' => $image,
                    'new_path' => $newPath
                ];
                $this->info("Product #{$product->id} ('{$product->name}'): Legacy path '{$image}' will be standardized to '{$newPath}'");
            } else {
                $this->error("Product #{$product->id} ('{$product->name}'): File '{$newPath}' NOT found in public storage! Skipping migration to avoid breaking image.");
                $skipped++;
            }
        }

        if (empty($updatesQueue)) {
            $this->info("\nNo legacy image paths need standardization.");
        } else {
            $count = count($updatesQueue);
            if ($dryRun) {
                $this->info("\nDry run completed. {$count} records would be updated.");
            } else {
                $this->info("\nApplying changes inside database transaction...");
                try {
                    DB::transaction(function () use ($updatesQueue, &$updated) {
                        foreach ($updatesQueue as $update) {
                            $product = $update['product'];
                            $product->image = $update['new_path'];
                            $product->save();
                            $updated++;
                        }
                    });
                    $this->info("Database transaction committed successfully!");
                } catch (\Exception $e) {
                    $this->error("An error occurred during migration: " . $e->getMessage());
                    $this->error("Database transaction rolled back safely.");
                    return Command::FAILURE;
                }
            }
        }

        $this->line("\n================ SUMMARY ================");
        $this->line("Total records processed:     {$total}");
        $this->line("Already standardized:        {$alreadyStandard}");
        $this->line("Legacy formats detected:     {$legacyDetected}");
        if ($dryRun) {
            $this->line("Would be updated:            " . count($updatesQueue));
        } else {
            $this->line("Successfully updated:        {$updated}");
        }
        $this->line("Skipped (missing file):      {$skipped}");
        $this->line("=========================================\n");

        return Command::SUCCESS;
    }
}
