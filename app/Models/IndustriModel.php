<?php

namespace App\Models;

use App\Models\KotaModel;
use App\Models\KategoriIndustriModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustriModel extends Model
{
    use HasFactory;
    protected $table = 'm_industri';
    protected $primaryKey = 'industri_id';
    public $timestamps = false;

    protected $fillable = ['industri_nama', 'kota_id', 'kategori_industri_id'];

    function kategori_industri()
    {
        return $this->belongsTo(KategoriIndustriModel::class, 'kategori_industri_id', 'kategori_industri_id');
    }
    function kota()
    {
        return $this->belongsTo(KotaModel::class, 'kota_id', 'kota_id');
    }
}
