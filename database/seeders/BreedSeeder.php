<?php

namespace Database\Seeders;

use App\Models\Breed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $breeds = ['kampung','beagle','anggora','persia','germanshepher','dwarf','himaayan','retriever','hahvana','bulldog'];
        foreach ($breeds as $name) {
            Breed::create(['name' => $name]);
        }
    }
}
