<?php

namespace App\Models;

use App\Models\DosenModel;
use App\Models\LevelModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MahasiswaModel extends Model
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
}
