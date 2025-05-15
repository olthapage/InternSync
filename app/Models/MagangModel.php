<?php

namespace App\Models;

use App\Models\MahasiswaModel;
use App\Models\DetailLowonganModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MagangModel extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa_magang';
    protected $primaryKey = 'mahasiswa_magang_id';
    protected $fillable = [
        'mahasiswa_id',
        'lowongan_id',
        'status',
        'evaluasi'
    ];
    public $timestamps = true;

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
    public function lowongan()
    {
        return $this->belongsTo(DetailLowonganModel::class, 'lowongan_id', 'lowongan_id');
    }

}
