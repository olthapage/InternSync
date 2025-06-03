<?php

namespace App\Models;

use App\Models\DosenModel;
use App\Models\LevelModel;
use App\Models\MahasiswaSkillModel;
use App\Models\PortofolioMahasiswa;
use Illuminate\Database\Eloquent\Model;
use App\Models\MahasiswaPreferensiLokasiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MahasiswaModel extends Authenticatable
{
    use HasFactory;
    protected $table = 'm_mahasiswa';
    protected $primaryKey = 'mahasiswa_id';
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'foto',
        'telepon',
        'nim',
        'status',
        'status_verifikasi',
        'alasan',
        'ipk',
        'level_id',
        'prodi_id',
        'dosen_id',
        'dpa_id',
        'sertifikat_kompetensi',
        'pakta_integritas',
        'daftar_riwayat_hidup',
        'khs',
        'ktp',
        'ktm',
        'surat_izin_ortu',
        'bpjs',
        'sktm_kip',
        'proposal',
        'skor_ais',
        'organisasi',
        'lomba',
        'kasus',
        'sertifikat_organisasi',
        'sertifikat_lomba'
    ];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    public function level()
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function prodi()
    {
        return $this->belongsTo(ProdiModel::class, 'prodi_id', 'prodi_id');
    }
    public function magang()
    {
        return $this->hasOne(MagangModel::class, 'mahasiswa_id');
    }
    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
    }
    public function preferensiLokasi()
    {
        return $this->hasMany(MahasiswaPreferensiLokasiModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
    public function skills()
    {
        return $this->hasMany(MahasiswaSkillModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
    public function dosenPembimbing()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
    }
    public function dpa()
    {
        return $this->belongsTo(DosenModel::class, 'dpa_id', 'dosen_id');
    }
    public function portofolios()
    {
        return $this->hasMany(PortofolioMahasiswa::class, 'mahasiswa_id', 'mahasiswa_id');
    }
    public function pengajuan()
    {
        return $this->hasMany(PengajuanModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

}
