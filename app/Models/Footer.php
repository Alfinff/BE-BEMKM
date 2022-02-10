<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Footer extends Model
{
    use HasFactory;

    protected $table = 'footer';

    protected $fillable = [
        'subtitle',
        'alamat',
        'telepon',
        'email',
        'user_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class,'user_id', 'uuid');
    }
}
