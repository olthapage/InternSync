<?php

namespace App\Models;

use App\Models\DetailLowonganModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FasilitasModel extends Model
{
    use HasFactory;
    protected $table = 'm_fasilitas';
    protected $primaryKey = 'fasilitas_id';
    public $timestamps = false;
    protected $fillable = ['nama_fasilitas'];

    public function lowongan()
    {
        return $this->belongsToMany(DetailLowonganModel::class, 'lowongan_fasilitas', 'fasilitas_id', 'lowongan_id');
    }
}
