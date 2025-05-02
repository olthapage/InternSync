<?php

namespace App\Models;

use App\Models\UserIpkModel;
use App\Models\LevelUserModel;
use App\Models\DetailSkillModel;
use App\Models\DetailKompetensiModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserPreferensiLokasiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use HasFactory;
    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = ['nama_lengkap', 'email','foto', 'password', 'level_id', 'created_at'];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    public function level()
    {
        return $this->belongsTo(LevelUserModel::class, 'level_id');
    }

    public function preferensiLokasi()
    {
        return $this->hasMany(UserPreferensiLokasiModel::class, 'user_id');
    }

    public function skills()
    {
        return $this->belongsToMany(DetailSkillModel::class, 'user_skill', 'user_id', 'skill_id');
    }

    public function kompetensi()
    {
        return $this->belongsToMany(DetailKompetensiModel::class, 'user_kompetensi', 'user_id', 'kompetensi_id')->withPivot('nilai');
    }

    public function ipk()
    {
        return $this->hasOne(UserIpkModel::class, 'user_id');
    }
}
