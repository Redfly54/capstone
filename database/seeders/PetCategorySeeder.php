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
         $categories = ['cat', 'dog', 'rabbit'];
        foreach ($categories as $name) {
            PetCategory::create(['name' => $name]);
        }
    }
}
