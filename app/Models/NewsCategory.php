<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Category;

class NewsCategory extends Model
{
    use HasFactory;

    protected $table      = 'category';
    protected $fillable = [
        'uuid',
        'category_id',
        'news_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'uuid');
    }
}
