<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaKompetensiModel extends Model
{
    use HasFactory;
    protected $table = 'user_kompetensi';
    public $timestamps = false;

    protected $fillable = ['mahasiswa_id', 'kompetensi_id', 'nilai'];
}
