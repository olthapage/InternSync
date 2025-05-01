<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSkillModel extends Model
{
    use HasFactory;
    protected $table = 'm_detail_skill';
    protected $primaryKey = 'skill_id';
    public $timestamps = false;

    protected $fillable = ['skill_nama', 'kategori_skill_id'];
}
