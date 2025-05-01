<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriIndustriModel extends Model
{
    use HasFactory;
    protected $table = 'm_kategori_industri';
    protected $primaryKey = 'kategori_industri_id';
    public $timestamps = false;

    protected $fillable = ['kategori_nama'];
}
