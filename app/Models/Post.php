<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
     use HasFactory,SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'pet_name',
        'pet_category_id',
        'breed_id',
        'color_count',
        'age_id',
        'weight',
        'gender',
        'about_pet',
        'user_id',
        'pictures',
        'email',
        'phone',
        'address',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
    ];

    protected $casts = [
        'pictures' => 'array',
        'weight' => 'decimal:2',
    ];

    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(PetCategory::class, 'pet_category_id');
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class, 'breed_id', 'id');
    }

    public function age()
    {
        return $this->belongsTo(Age::class, 'age_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
