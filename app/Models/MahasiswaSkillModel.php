<?php

namespace App\Models;

use App\Models\MahasiswaModel;
use App\Models\PortofolioSkill;
use App\Models\DetailSkillModel;
use App\Models\PortofolioMahasiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MahasiswaSkillModel extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa_skill';
    protected $primaryKey = 'mahasiswa_skill_id';
    protected $fillable = ['mahasiswa_id', 'skill_id', 'status_verifikasi', 'level_kompetensi'];

    const UPDATED_AT = null;

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
    public function detailSkill()
    {
        return $this->belongsTo(DetailSkillModel::class, 'skill_id', 'skill_id');
    }
    public function linkedPortofolios()
    {
        return $this->belongsToMany(
            PortofolioMahasiswa::class,
            'portofolio_skill_pivot', // Nama tabel pivot
            'mahasiswa_skill_id',      // Foreign key di tabel pivot untuk MahasiswaSkillModel
            'portofolio_id'          // Foreign key di tabel pivot untuk PortofolioMahasiswa
        )->withPivot('deskripsi_penggunaan_skill', 'id'); // 'id' adalah PK tabel pivot
    }
      // Alternatif jika menggunakan model pivot secara eksplisit
    public function portfolioSkillLinks()
    {
        return $this->hasMany(PortofolioSkill::class, 'mahasiswa_skill_id', 'mahasiswa_skill_id');
    }
}
