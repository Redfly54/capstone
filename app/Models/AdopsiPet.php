<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdopsiPet extends Model
{
     use HasFactory;

    protected $table = 'adopsi_pets';

    protected $fillable = [
        'pet_name',
        'pet_category_id',
        'breed_id',
        'color',
        'age_id',
        'weight',
        'gender',
        'about_pet',
        'pictures',
        'user_id',
        'pengganti_id',
    ];

    protected $casts = [
        'pictures' => 'array',
        'weight' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(PetCategory::class, 'pet_category_id');
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function age()
    {
        return $this->belongsTo(Age::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengganti()
    {
        return $this->belongsTo(User::class, 'pengganti_id');
    }
}
