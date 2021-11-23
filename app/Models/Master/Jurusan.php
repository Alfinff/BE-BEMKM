<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;
    protected $table = 'master_jurusan';
    protected $fillable = [
        'uuid',
        'faculty_id',
        'name',
        'major_code'
    ];
}
