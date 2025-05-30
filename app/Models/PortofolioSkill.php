<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Model standar, bukan Pivot jika ada PK 'id' sendiri
// Jika tabel pivot Anda hanya berisi foreign keys tanpa PK 'id' atau timestamps,
// Anda bisa menggunakan Illuminate\Database\Eloquent\Relations\Pivot.
// Namun, karena kita mungkin punya 'id' dan 'deskripsi_penggunaan_skill', Model standar lebih fleksibel.

class PortofolioSkill extends Model
{
    use HasFactory;

    protected $table = 'portofolio_skill_pivot'; // Nama tabel pivot Anda
    // Laravel akan mengasumsikan primary key adalah 'id' jika tidak dispesifikasikan.
    // public $incrementing = true; // Default untuk primary key 'id'

    // Jika tabel pivot Anda memiliki timestamps (created_at, updated_at)
    public $timestamps = true; // Sesuaikan dengan migrasi tabel pivot Anda

    protected $fillable = [
        'portofolio_id',
        'mahasiswa_skill_id',
        'deskripsi_penggunaan_skill',
    ];

    /**
     * Mendapatkan item portofolio yang terkait.
     */
    public function portofolio()
    {
        return $this->belongsTo(PortofolioMahasiswa::class, 'portofolio_id', 'portofolio_id');
    }

    /**
     * Mendapatkan entri skill mahasiswa yang terkait.
     */
    public function mahasiswaSkill()
    {
        return $this->belongsTo(MahasiswaSkillModel::class, 'mahasiswa_skill_id', 'mahasiswa_skill_id');
    }
}
