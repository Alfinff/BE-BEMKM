<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Beasiswa extends Model
{
    use HasFactory;

    protected $table = 'beasiswa';
    protected $appends = [
        'picture_formated'
    ];
    protected $fillable = [
        'uuid',
        'title',
        'content',
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
}
