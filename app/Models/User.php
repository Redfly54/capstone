<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'username',
        'email',
        'password',
        'phone',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'description',
        'picture',
        'terms',
        'is_admin',
        'is_active',
    ];

    public function reference()
    {
        return $this->hasOne(Reference::class, 'user_id', 'user_id');
    }

    public function post()
    {
        return $this->hasMany(Post::class, 'user_id', 'user_id');
    }

    public function result()
    {
        return $this->hasOne(Result::class, 'user_id', 'user_id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'terms' => 'boolean',
            'is_admin' => 'boolean',
            'created_at' => 'datetime',
        ];
    }
}
