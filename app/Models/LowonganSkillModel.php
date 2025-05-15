<?php

namespace App\Models;

use App\Models\DetailSkillModel;
use App\Models\DetailLowonganModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LowonganSkillModel extends Model
{
    use HasFactory;
    protected $table = 'lowongan_skill';
    protected $primaryKey = 'lowongan_skill_id';
    public $timestamps = false;

    protected $fillable = ['lowongan_id', 'skill_id', 'bobot'];
    public function lowongan()
    {
        return $this->belongsTo(DetailLowonganModel::class, 'lowongan_id', 'lowongan_id');
    }
    public function skill()
    {
        return $this->belongsTo(DetailSkillModel::class, 'skill_id', 'skill_id');
    }
}
