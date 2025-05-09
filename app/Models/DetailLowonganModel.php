<?php

namespace App\Models;

use App\Models\IndustriModel;
use App\Models\KategoriLowonganModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailLowonganModel extends Model
{
    use HasFactory;
    protected $table = 'm_detail_lowongan';
    protected $primaryKey = 'lowongan_id';
    public $timestamps = false;

    protected $fillable = ['judul_lowongan', 'deskripsi', 'industri_id'];

    public function industri()
    {
        return $this->belongsTo(IndustriModel::class, 'industri_id');
    }
}
