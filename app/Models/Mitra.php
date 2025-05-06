<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mitra extends Model
{
    use HasFactory, SoftDeletes;

    // Nama tabel (opsional jika sesuai konvensi Laravel)
    protected $table = 'mitra';

    // Kolom yang boleh diisi melalui mass assignment
    protected $fillable = [
        'nama',
        'alamat',
        'status',
    ];

    // (Opsional) Konversi tipe data otomatis jika diperlukan
    protected $casts = [
        'status' => 'string',
    ];
}
