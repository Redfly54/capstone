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
        'user_id',
        'posts',
    ];

    protected $casts = [
        'posts' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}