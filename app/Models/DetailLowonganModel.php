<?php
namespace App\Models;

use Carbon\Carbon;
use App\Models\MagangModel;
use App\Models\IndustriModel;
use App\Models\PengajuanModel;
use App\Models\KategoriSkillModel;
use App\Models\LowonganSkillModel;
use App\Models\KriteriaMagangModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailLowonganModel extends Model
{
    use HasFactory;
    protected $table      = 'm_detail_lowongan';
    protected $primaryKey = 'lowongan_id';
    public $timestamps    = false;

    protected $fillable = ['judul_lowongan', 'slot', 'deskripsi', 'industri_id', 'tanggal_mulai', 'tanggal_selesai', 'kategori_skill_id', 'pendaftaran_tanggal_mulai', 'pendaftaran_tanggal_selesai'];

    protected $casts = [
        'tanggal_mulai'               => 'date',
        'tanggal_selesai'             => 'date',
        'pendaftaran_tanggal_mulai'   => 'date',
        'pendaftaran_tanggal_selesai' => 'date',
    ];

    public function industri()
    {
        return $this->belongsTo(IndustriModel::class, 'industri_id');
    }
    public function kriteriaMagang()
    {
        return $this->hasOne(KriteriaMagangModel::class, 'lowongan_id', 'lowongan_id');
    }
    public function kategoriSkill()
    {
        return $this->belongsTo(KategoriSkillModel::class, 'kategori_skill_id', 'kategori_skill_id');
    }
    public function lowonganSkill()
    {
        return $this->hasMany(LowonganSkillModel::class, 'lowongan_id', 'lowongan_id');
    }
    public function slotTerisi(): int
    {
        return $this->hasMany(MagangModel::class, 'lowongan_id', 'lowongan_id')
            ->where('status', 'diterima')
            ->count();
    }
    public function slotTersedia(): int
    {
        return max(0, $this->slot - $this->slotTerisi());
    }
    public function pengajuanMagangCount()
    {
        return $this->hasMany(PengajuanModel::class, 'lowongan_id', 'lowongan_id')->count();
    }
    public function pendaftar()
    {
        return $this->hasMany(PengajuanModel::class, 'lowongan_id', 'lowongan_id');
    }

    // Accessor untuk mendapatkan teks status pendaftaran
    public function getStatusPendaftaranTextAttribute()
    {
        $now     = Carbon::now();
        $mulai   = $this->pendaftaran_tanggal_mulai;
        $selesai = $this->pendaftaran_tanggal_selesai;

        if (! $mulai || ! $selesai) {
            return 'Periode Pendaftaran Belum Diatur';
        }

        if ($now->lt($mulai->startOfDay())) {
            return 'Pendaftaran Akan Datang';
        } elseif ($now->between($mulai->startOfDay(), $selesai->endOfDay(), true)) { // true untuk inklusif
            return 'Pendaftaran Dibuka';
        } else {
            return 'Pendaftaran Ditutup';
        }
    }

    // Accessor untuk mendapatkan kelas badge Bootstrap berdasarkan status
    public function getStatusPendaftaranBadgeClassAttribute()
    {
        $now     = Carbon::now();
        $mulai   = $this->pendaftaran_tanggal_mulai;
        $selesai = $this->pendaftaran_tanggal_selesai;

        if (! $mulai || ! $selesai) {
            return 'secondary'; // Default badge jika tanggal tidak lengkap
        }

        if ($now->lt($mulai->startOfDay())) {
            return 'info'; // Akan Datang (biru muda)
        } elseif ($now->between($mulai->startOfDay(), $selesai->endOfDay(), true)) {
            return 'success'; // Dibuka (hijau)
        } else {
            return 'danger'; // Ditutup (merah)
        }
    }
}
