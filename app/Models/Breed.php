<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function adopsiPets()
    {
        return $this->hasMany(AdopsiPet::class);
    }
}
