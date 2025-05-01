<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaModel extends Model
{
    use HasFactory;
    protected $table = 'm_kota';
    protected $primaryKey = 'kota_id';
    public $timestamps = false;

    protected $fillable = ['kota_nama', 'provinsi_id'];

    public function provinsi()
    {
        return $this->belongsTo(ProvinsiModel::class, 'provinsi_id');
    }

    public function industri()
    {
        return $this->hasMany(IndustriModel::class, 'kota_id');
    }
}
