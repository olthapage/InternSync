<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelUserModel extends Model
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
