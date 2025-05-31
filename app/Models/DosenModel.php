<?php

namespace App\Models;

use App\Models\LevelModel;
use App\Models\MahasiswaModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DosenModel extends Authenticatable
{
    use HasFactory;
    protected $table = 'm_dosen';
    protected $primaryKey = 'dosen_id';
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'foto',
        'telepon',
        'nip',
        'role_dosen',
        'level_id',
        'prodi_id'
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
    // Mahasiswa yang dibimbing magangnya oleh dosen ini
    public function mahasiswaBimbinganMagang()
    {
        return $this->hasMany(MahasiswaModel::class, 'dosen_id', 'dosen_id');
    }

    // Mahasiswa yang diampu sebagai DPA oleh dosen ini
    public function mahasiswaWali() // atau mahasiswaAsuhanDpa
    {
        return $this->hasMany(MahasiswaModel::class, 'dpa_id', 'dosen_id');
    }
    public function isDpa(): bool
    {
        return $this->role_dosen === 'dpa';
    }

    public function isPembimbing(): bool
    {
        return $this->role_dosen === 'pembimbing';
    }
}
