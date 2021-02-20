<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'language_id',
        'title',
        'description',
        'body',
        'slug'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
