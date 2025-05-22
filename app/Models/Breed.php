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

    public function posts()
    {
        return $this->hasMany(Post::class, 'breed_id');
    }

    public function category()
    {
        return $this->belongsTo(PetCategory::class, 'pet_category_id');
    }
}
