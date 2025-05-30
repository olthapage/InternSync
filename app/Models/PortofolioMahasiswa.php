<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortofolioMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'portofolio_mahasiswa';
    protected $primaryKey = 'portofolio_id';

    protected $fillable = [
        'mahasiswa_id',
        'judul_portofolio',
        'deskripsi_portofolio',
        'tipe_portofolio', // ENUM: 'file', 'url', 'gambar', 'video'
        'lokasi_file_atau_url',
        'tanggal_pengerjaan_mulai',
        'tanggal_pengerjaan_selesai',
    ];

    protected $casts = [
        'tanggal_pengerjaan_mulai' => 'date',
        'tanggal_pengerjaan_selesai' => 'date',
    ];

    /**
     * Mendapatkan data mahasiswa yang memiliki portofolio ini.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    /**
     * Mendapatkan entri skill mahasiswa yang terkait dengan portofolio ini melalui tabel pivot.
     * Ini adalah cara untuk mendapatkan SEMUA MahasiswaSkill yang dilink ke portofolio ini.
     */
    public function linkedMahasiswaSkills()
    {
        return $this->belongsToMany(
            MahasiswaSkillModel::class,
            'portofolio_skill_pivot', // Nama tabel pivot
            'portofolio_id',          // Foreign key di tabel pivot untuk PortofolioMahasiswa
            'mahasiswa_skill_id'      // Foreign key di tabel pivot untuk MahasiswaSkillModel
        )->withPivot('deskripsi_penggunaan_skill', 'id') // 'id' adalah PK tabel pivot jika Anda ingin mengaksesnya
         ->withTimestamps(); // Jika tabel pivot Anda memiliki timestamps
    }

    // Jika Anda membuat model untuk tabel pivot (misal PortofolioSkill), relasinya bisa juga:
    // /**
    //  * Mendapatkan semua link ke skill mahasiswa untuk portofolio ini.
    //  */
    public function skillLinks()
    {
        return $this->hasMany(PortofolioSkill::class, 'portofolio_id', 'portofolio_id');
    }
}
