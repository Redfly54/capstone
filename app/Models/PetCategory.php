<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function adopsiPets()
    {
        return $this->hasMany(AdopsiPet::class, 'pet_category_id');
    }
}
