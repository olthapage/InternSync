<?php

namespace App\Models;

use App\Models\KotaModel;
use App\Models\MagangModel;
use App\Models\LowonganDetailModel;
use App\Models\KategoriIndustriModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class IndustriModel extends Authenticatable
{
    use HasFactory;
    protected $table = 'm_industri';
    protected $primaryKey = 'industri_id';
    public $timestamps = false;

    protected $fillable = ['industri_nama', 'kota_id', 'kategori_industri_id', 'email', 'telepon', 'password', 'logo', 'alumni_count'];

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
        public function getLogoUrlAttribute()
    {
        if ($this->logo && Storage::disk('public')->exists('logo_industri/' . $this->logo)) {
            return asset('storage/logo_industri/' . $this->logo);
        }
        return asset('assets/default-industri.png'); // Pastikan path ini benar
    }
    protected function totalAlumniCount(): Attribute
    {
        return Attribute::make(
            get: fn () => MagangModel::whereHas('lowongan', function ($query) {
                    $query->where('industri_id', $this->industri_id);
                })
                ->where('status', 'selesai') // Pastikan status ini benar
                ->count()
        );
    }
    public function getAlumniCountByProdi(int $prodiId): int
    {
        return MagangModel::whereHas('lowongan', function ($query) {
                $query->where('industri_id', $this->industri_id);
            })
            ->whereHas('mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->where('status', 'selesai')
            ->count();
    }
}
