<?php

namespace App\Models;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelModel extends Model
{
    use HasFactory;
    protected $table = 'm_level_user';
    protected $primaryKey = 'level_id';
    public $timestamps = false;

    protected $fillable = ['level_nama'];

    public function users()
    {
        return $this->hasMany(UserModel::class, 'level_id');
    }
}
