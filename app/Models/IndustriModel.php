<?php

namespace App\Models;

use App\Models\KotaModel;
use App\Models\KategoriIndustriModel;
use App\Models\LowonganDetailModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class IndustriModel extends Authenticatable
{
    use HasFactory;
    protected $table = 'm_industri';
    protected $primaryKey = 'industri_id';
    public $timestamps = false;

    protected $fillable = ['industri_nama', 'kota_id', 'kategori_industri_id', 'email', 'telepon', 'password', 'logo'];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    function kategori_industri()
    {
        return $this->belongsTo(KategoriIndustriModel::class, 'kategori_industri_id', 'kategori_industri_id');
    }
    function kota()
    {
        return $this->belongsTo(KotaModel::class, 'kota_id', 'kota_id');
    }
    public function detail_lowongan()
    {
        return $this->hasMany(DetailLowonganModel::class, 'industri_id');
    }
}
