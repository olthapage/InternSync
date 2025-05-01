<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKompetensiModel extends Model
{
    use HasFactory;
    protected $table = 'm_detail_kompetensi';
    protected $primaryKey = 'kompetensi_id';
    public $timestamps = false;

    protected $fillable = ['nama_matkul', 'kategori_kompetensi_id'];
}
