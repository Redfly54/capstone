<?php

namespace Database\Seeders;

use App\Models\PetCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
        ['name' => 'cat', 'icon' => 'images/cat.png'],
        ['name' => 'dog', 'icon' => 'images/dog.png'],
        ['name' => 'rabbit', 'icon' => 'images/rabbit.png'],
        ];

        foreach ($categories as $category) {
            PetCategory::create($category);
        }
        }
}
