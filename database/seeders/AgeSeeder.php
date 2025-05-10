<?php

namespace Database\Seeders;

use App\Models\Age;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $ages = [
            ['category' => 'young', 'description' => '0-1 tahun'],
            ['category' => 'adult', 'description' => '1-7 tahun'],
            ['category' => 'senior', 'description' => '7 tahun ke atas'],
        ];
        foreach ($ages as $data) {
            Age::create($data);
        }
    }
}
