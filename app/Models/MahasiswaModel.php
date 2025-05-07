<?php

namespace App\Models;

use App\Models\DosenModel;
use App\Models\LevelModel;
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
        'ipk',
        'nim',
        'status',
        'level_id',
        'prodi_id',
        'dosen_id'
    ];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    public function level()
    {
        return $this->belongsTo(LevelModel::class, 'level_id');
    }
    public function prodi()
    {
        return $this->belongsTo(ProdiModel::class, 'prodi_id');
    }
    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id');
    }
    public function preferensiLokasi()
    {
        return $this->hasMany(MahasiswaPreferensiLokasiModel::class, 'mahasiswa_id');
    }
    public function skills()
    {
        return $this->belongsToMany(DetailSkillModel::class, 'user_skill', 'mahasiswa_id', 'skill_id');
    }
    public function kompetensi()
    {
        return $this->belongsToMany(DetailKompetensiModel::class, 'user_kompetensi', 'mahasiswa_id', 'kompetensi_id')->withPivot('nilai');
    }
}
