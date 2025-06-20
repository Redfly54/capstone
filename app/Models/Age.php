<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Age extends Model
{
     use HasFactory;

    protected $fillable = [
        'category',
        'description',
    ];

    public function posts()
    {
        return $this->hasMany(AdopsiPet::class);
    }
}
