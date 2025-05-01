<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKompetensiModel extends Model
{
    use HasFactory;
    protected $table = 'm_kategori_kompetensi';
    protected $primaryKey = 'kategori_kompetensi_id';
    public $timestamps = false;

    protected $fillable = ['kategori_nama'];
}
