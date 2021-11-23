<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'content',
        'status',
        'picture',
        'user_id'
    ];
}
