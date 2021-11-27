<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\NewsCategory;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $appends = [
        'picture_formated'
    ];
    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'content',
        'status',
        'picture',
        'user_id'
    ];

    public function getPictureFormatedAttribute()
    {
        if ($this->attributes['picture']) {
            return showFile($this->attributes['picture']);
        }

        return '';
    }

    public function author()
    {
        return $this->belongsTo(User::class,'user_id', 'uuid');
    }

    public function newsCategory()
    {
        return $this->hasMany(NewsCategory::class, 'news_id', 'uuid');
    }
}
