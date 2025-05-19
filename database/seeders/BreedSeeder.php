<?php

namespace Database\Seeders;

use App\Models\Breed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PetCategory;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $breeds = [
            'dog' => ['Bulldog', 'kampung', 'Retriever', 'beagle', 'German Shepherd', 'another'],
            'cat' => [ 'Persia', 'kampung', 'anggora', 'siam', 'Bengal', 'another'],
            'rabbit' => ['lop', 'dwar', 'anggora', 'himalayan', 'havana', 'another'],
        ];

        foreach ($breeds as $category => $breedList) {
            $categoryModel = PetCategory::where('name', $category)->first();
            if ($categoryModel) {
                foreach ($breedList as $breed) {
                    Breed::create([
                        'name' => $breed,
                        'pet_category_id' => $categoryModel->id,
                    ]);
                }
            }
        }
    }
}
