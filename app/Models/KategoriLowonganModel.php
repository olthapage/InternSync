<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLowonganModel extends Model
{
    use HasFactory;
    protected $table = 'm_kategori_lowongan';
    protected $primaryKey = 'kategori_lowongan_id';
    public $timestamps = false;

    protected $fillable = ['kategori_nama'];
}
