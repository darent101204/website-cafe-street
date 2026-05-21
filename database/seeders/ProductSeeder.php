<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Vanilla Latte',
                'description' => 'Smooth vanilla latte with premium coffee beans',
                'price' => 21000,
                'image' => 'products/vanilla.png',
                'rating' => 4.5,
                'category' => 'hot',
                'is_featured' => true
            ],
            [
                'name' => 'Espresso',
                'description' => 'Strong and bold espresso shot',
                'price' => 21000,
                'image' => 'products/espresso.png',
                'rating' => 4.5,
                'category' => 'hot',
                'is_featured' => true
            ],
            [
                'name' => 'Hazelnut Latte',
                'description' => 'Rich hazelnut flavored latte',
                'price' => 21000,
                'image' => 'products/hazelnut.png',
                'rating' => 4.5,
                'category' => 'hot',
                'is_featured' => true
            ],
            [
                'name' => 'Sandwich',
                'description' => 'Bread with meat and vegetables',
                'price' => 12000,
                'image' => 'products/img_product.png',
                'rating' => 4.5,
                'category' => 'hot',
                'is_featured' => false
            ],
            [
                'name' => 'Hot Milk',
                'description' => 'Hot milk with less sugar',
                'price' => 12000,
                'image' => 'products/hot-milk.png',
                'rating' => 4.5,
                'category' => 'hot',
                'is_featured' => false
            ],
            [
                'name' => 'Coffee Ice Cream',
                'description' => 'Coffee ice cream with chocolate',
                'price' => 12000,
                'image' => 'products/ice_cream.png',
                'rating' => 4.5,
                'category' => 'cold',
                'is_featured' => false
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Classic cappuccino with foam',
                'price' => 12000,
                'image' => 'products/cappuciono.png',
                'rating' => 4.5,
                'category' => 'hot',
                'is_featured' => false
            ],
            [
                'name' => 'Mocca',
                'description' => 'Chocolate coffee blend',
                'price' => 12000,
                'image' => 'products/mocca.png',
                'rating' => 4.5,
                'category' => 'hot',
                'is_featured' => false
            ],
            [
                'name' => 'Waffle Ice Cream',
                'description' => 'Crispy waffle with ice cream',
                'price' => 12000,
                'image' => 'products/waffle.png',
                'rating' => 4.5,
                'category' => 'cold',
                'is_featured' => false
            ]
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['name' => $product['name']], $product);
        }
    }
}
