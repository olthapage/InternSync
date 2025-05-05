<?php

namespace App\Models;

use App\Models\LevelModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DosenModel extends Model
{
    use HasFactory;
    protected $table = 'm_dosen';
    protected $primaryKey = 'dosen_id';
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'nip',
        'level_id',
        'prodi_id'
    ];
    public function level()
    {
        return $this->belongsTo(LevelModel::class, 'level_id');
    }
    public function prodi()
    {
        return $this->belongsTo(ProdiModel::class, 'prodi_id');
    }

}
