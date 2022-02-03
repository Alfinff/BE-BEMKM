<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class VisiMisi extends Model
{
    use HasFactory;

    protected $table = 'visi_misi';
    protected $appends = [
        'picture_formated'
    ];
    protected $fillable = [
        'title',
        'visi',
        'misi',
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

    public function getCreatedAtAttribute($value)
    {
        return formatTanggal($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return formatTanggal($value);
    }
}
