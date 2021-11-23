<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;
    protected $table = 'master_fakultas';
    protected $fillable = [
        'uuid',
        'name',
        'faculty_code',
        'number_of_major',
    ];
}
