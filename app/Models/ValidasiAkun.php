<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidasiAkun extends Model
{
    use HasFactory;

    protected $table = 'validasi_akun';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'username',
        'password',
        'perkiraan_role',
        'status_validasi',
        'alasan_penolakan',
    ];

    protected $hidden = [
        'password',
    ];
}
