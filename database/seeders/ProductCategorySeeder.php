<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Home Appliances',
        ]);
        Category::create([
            'name' => 'Electronics',
        ]);
        Category::create([
            'name' => 'Real Estate',
        ]);
        Category::create([
            'name' => 'Sports',
        ]);
        Category::create([
            'name' => 'Dress',
        ]);
        Category::create([
            'name' => 'Auto Spares',
        ]);
        Category::create([
            'name' => 'Technical Devices',
        ]);
    }
}
