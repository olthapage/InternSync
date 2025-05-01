<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKompetensiModel extends Model
{
    use HasFactory;
    protected $table = 'user_kompetensi';
    public $timestamps = false;

    protected $fillable = ['user_id', 'kompetensi_id', 'nilai'];
}
