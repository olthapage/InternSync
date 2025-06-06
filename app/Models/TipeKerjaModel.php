<?php

namespace App\Models;

use App\Models\DetailLowonganModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipeKerjaModel extends Model
{
    use HasFactory;
    protected $table = 'm_tipe_kerja';
    protected $primaryKey = 'tipe_kerja_id';
    public $timestamps = false;
    protected $fillable = ['nama_tipe_kerja'];

    public function lowongan()
    {
        return $this->belongsToMany(DetailLowonganModel::class, 'lowongan_tipe_kerja', 'tipe_kerja_id', 'lowongan_id');
    }
}
