<?php

namespace App\Models;

use App\Models\MahasiswaModel;
use App\Models\DetailSkillModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MahasiswaSkillModel extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa_skill';
    protected $primaryKey = 'mahasiswa_skill_id';
    protected $fillable = ['mahasiswa_id', 'skill_id', 'bobot'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
    public function skill()
    {
        return $this->belongsTo(DetailSkillModel::class, 'skill_id', 'skill_id');
    }
}
