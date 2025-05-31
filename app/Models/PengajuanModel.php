<?php

namespace App\Models;

use App\Models\MahasiswaModel;
use App\Models\DetailLowonganModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanModel extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 't_pengajuan';

    // Primary key
    protected $primaryKey = 'pengajuan_id';

    // Kolom-kolom yang dapat diisi
    protected $fillable = [
        'mahasiswa_id',
        'lowongan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan_penolakan',
        'status'
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    // Relasi ke Lowongan
    public function lowongan()
    {
        return $this->belongsTo(DetailLowonganModel::class, 'lowongan_id');
    }
}
