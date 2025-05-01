<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLowonganModel extends Model
{
    use HasFactory;
    protected $table = 'm_detail_lowongan';
    protected $primaryKey = 'lowongan_id';
    public $timestamps = false;

    protected $fillable = ['judul_lowongan', 'deskripsi', 'industri_id', 'kategori_lowongan_id'];
}
