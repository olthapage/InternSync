<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaSkillModel extends Model
{
    use HasFactory;
    protected $table = 'user_skill';
    protected $fillable = ['mahasiswa_id', 'skill_id'];

}
