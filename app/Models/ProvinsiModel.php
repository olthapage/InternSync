<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvinsiModel extends Model
{
    use HasFactory;
    protected $table = 'm_provinsi';
    protected $primaryKey = 'provinsi_id';
    public $timestamps = false;

    protected $fillable = ['provinsi_nama'];

    public function kota()
    {
        return $this->hasMany(KotaModel::class, 'provinsi_id');
    }
}
