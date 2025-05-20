<?php

namespace App\Models;

use App\Models\IndustriModel;
use App\Models\LowonganSkillModel;
use App\Models\KriteriaMagangModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailLowonganModel extends Model
{
    use HasFactory;
    protected $table = 'm_detail_lowongan';
    protected $primaryKey = 'lowongan_id';
    public $timestamps = false;

    protected $fillable = ['judul_lowongan', 'slot', 'deskripsi', 'industri_id', 'tanggal_mulai', 'tanggal_selesai'];

    public function industri()
    {
        return $this->belongsTo(IndustriModel::class, 'industri_id');
    }
    public function kriteriaMagang()
    {
        return $this->hasOne(KriteriaMagangModel::class, 'lowongan_id', 'lowongan_id');
    }
    public function lowonganSkill()
    {
        return $this->hasMany(LowonganSkillModel::class, 'lowongan_id', 'lowongan_id');
    }
}
