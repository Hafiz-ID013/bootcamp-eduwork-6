<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'category_id' => 1,
                'name' => 'Black Hoodie',
                'description' => 'Premium cotton hoodie',
                'stock' => 20,
                'price' => 200000,
                'image' => 'products/hoodie.jpg',
            ],
            [
                'category_id' => 1,
                'name' => 'White T-Shirt',
                'description' => 'Basic oversized t-shirt',
                'stock' => 50,
                'price' => 120000,
                'image' => 'products/tshirt.jpg',
            ],
        ]);
    }
}
