<?php
namespace App\Models;

use App\Models\FasilitasModel;
use App\Models\IndustriModel;
use App\Models\KategoriSkillModel;
use App\Models\KotaModel;
use App\Models\KriteriaMagangModel;
use App\Models\LowonganSkillModel;
use App\Models\MagangModel;
use App\Models\PengajuanModel;
use App\Models\ProvinsiModel;
use App\Models\TipeKerjaModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLowonganModel extends Model
{
    use HasFactory;
    protected $table      = 'm_detail_lowongan';
    protected $primaryKey = 'lowongan_id';
    public $timestamps    = false;

    protected $fillable = ['judul_lowongan', 'slot', 'deskripsi', 'industri_id', 'tanggal_mulai', 'tanggal_selesai', 'kategori_skill_id', 'pendaftaran_tanggal_mulai', 'pendaftaran_tanggal_selesai',
        'use_specific_location',
        'lokasi_provinsi_id',
        'lokasi_kota_id',
        'lokasi_alamat_lengkap',
        'upah',];

    protected $casts = [
        'tanggal_mulai'               => 'date',
        'tanggal_selesai'             => 'date',
        'pendaftaran_tanggal_mulai'   => 'date',
        'pendaftaran_tanggal_selesai' => 'date',
        'use_specific_location'       => 'boolean',
    ];

    public function industri()
    {
        return $this->belongsTo(IndustriModel::class, 'industri_id');
    }
    // Relasi untuk lokasi spesifik lowongan
    public function lokasiProvinsi()
    {
        return $this->belongsTo(ProvinsiModel::class, 'lokasi_provinsi_id', 'provinsi_id'); // Sesuaikan PK provinsi
    }

    public function lokasiKota()
    {
        return $this->belongsTo(KotaModel::class, 'lokasi_kota_id', 'kota_id'); // Sesuaikan PK kota
    }
    // Accessor untuk menampilkan alamat lengkap lowongan secara dinamis
    public function getAlamatLengkapDisplayAttribute()
    {
        if ($this->use_specific_location && $this->lokasi_kota_id) {
            // Gunakan alamat spesifik lowongan
            $alamat   = $this->lokasi_alamat_lengkap ?? '';
            $kota     = optional($this->lokasiKota)->kota_nama ?? '';
            $provinsi = optional($this->lokasiProvinsi)->provinsi_nama ?? ''; // Atau dari $this->lokasiKota->provinsi->provinsi_nama

            if ($kota && $provinsi) {
                return trim("$alamat, $kota, $provinsi", ", ");
            } elseif ($kota) {
                return trim("$alamat, $kota", ", ");
            }
            return $alamat ?: 'Alamat spesifik belum lengkap';
        } elseif ($this->industri) {
                                                                      // Gunakan alamat industri
            $alamatIndustri   = $this->industri->alamat_detail ?? ''; // Asumsi IndustriModel punya 'alamat_detail'
            $kotaIndustri     = optional($this->industri->kota)->kota_nama ?? '';
            $provinsiIndustri = optional(optional($this->industri->kota)->provinsi)->provinsi_nama ?? '';

            if ($kotaIndustri && $provinsiIndustri) {
                return trim("$alamatIndustri, $kotaIndustri, $provinsiIndustri", ", ");
            } elseif ($kotaIndustri) {
                return trim("$alamatIndustri, $kotaIndustri", ", ");
            }
            return $alamatIndustri ?: 'Alamat industri tidak tersedia';
        }
        return 'Alamat tidak tersedia';
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
    public function fasilitas()
    {
        return $this->belongsToMany(FasilitasModel::class, 'lowongan_fasilitas', 'lowongan_id', 'fasilitas_id');
    }

    // Relasi baru untuk Tipe Kerja (Many-to-Many)
    public function tipeKerja()
    {
        return $this->belongsToMany(TipeKerjaModel::class, 'lowongan_tipe_kerja', 'lowongan_id', 'tipe_kerja_id');
    }
}
