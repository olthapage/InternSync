<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSkillModel extends Model
{
    use HasFactory;
    protected $table = 'm_kategori_skill';
    protected $primaryKey = 'kategori_skill_id';
    public $timestamps = false;

    protected $fillable = ['kategori_nama'];

    public function skills()
    {
        return $this->hasMany(DetailSkillModel::class, 'kategori_skill_id');
    }
}
