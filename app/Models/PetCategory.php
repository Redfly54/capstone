<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'name',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'pet_category_id');
    }

    public function breeds()
    {
        return $this->hasMany(Breed::class, 'pet_category_id');
    }
}
