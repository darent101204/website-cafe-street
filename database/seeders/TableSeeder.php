<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            [
                'table_number' => 'A1',
                'qr_token' => 'table-a1',
                'status' => 'available'
            ],
            [
                'table_number' => 'A2',
                'qr_token' => 'table-a2',
                'status' => 'available'
            ],
            [
                'table_number' => 'A3',
                'qr_token' => 'table-a3',
                'status' => 'available'
            ],
            [
                'table_number' => 'A4',
                'qr_token' => 'table-a4',
                'status' => 'available'
            ],
        ];

        foreach ($tables as $table) {
            Table::updateOrCreate(
                ['table_number' => $table['table_number']],
                ['qr_token' => $table['qr_token'], 'status' => $table['status']]
            );
        }
    }
}
