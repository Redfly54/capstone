<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'animal_type',
        'breed',
        'animal_gender',
        'age_group',
        'color_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function animalType()
    {
        return $this->belongsTo(PetCategory::class, 'animal_type', 'pet_category_id');
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class, 'breed', 'id');
    }

    public function age()
    {
        return $this->belongsTo(Age::class,'age_group','id');
    }
}