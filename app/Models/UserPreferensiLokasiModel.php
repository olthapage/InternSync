<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreferensiLokasiModel extends Model
{
    use HasFactory;
    protected $table = 'user_preferensi_lokasi';
    public $timestamps = false;

    protected $fillable = ['user_id', 'kota_id', 'prioritas', 'created_at'];
}
