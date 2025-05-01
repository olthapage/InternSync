<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganSkillModel extends Model
{
    use HasFactory;
    protected $table = 'lowongan_skill';
    public $timestamps = false;

    protected $fillable = ['lowongan_id', 'skill_id'];
}
