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

    protected $fillable = [
        'title',
        'visi',
        'misi',
        'picture',
        'user_id'
    ];

    public function getPictureAttribute($value)
    {
        if ($value == '') {
            // return Storage::disk('s3')->temporaryUrl("images/avatar-mahasiswa.svg", Carbon::now()->addMinutes(5));
            return '';
        } else {
            return Storage::disk('s3')->temporaryUrl($value, Carbon::now()->addMinutes(5));
        }

        return $value;
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
