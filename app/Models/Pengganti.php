<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengganti extends Model
{
    use HasFactory;

    protected $table = 'pengganti';

    protected $fillable = [
        'email',
        'handphonenumber',
        'address',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
    ];

    public function adopsiPets()
    {
        return $this->hasMany(AdopsiPet::class, 'pengganti_id');
    }
}
