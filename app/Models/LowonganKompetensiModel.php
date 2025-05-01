<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganKompetensiModel extends Model
{
    use HasFactory;
    protected $table = 'lowongan_kompetensi';
    public $timestamps = false;

    protected $fillable = ['lowongan_id', 'kompetensi_id'];
}
