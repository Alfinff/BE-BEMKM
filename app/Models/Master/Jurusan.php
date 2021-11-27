<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Jurusan;

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

    public function faculty()
    {
        return $this->belongsTo(Fakultas::class,'faculty_id', 'uuid');
    }
}
