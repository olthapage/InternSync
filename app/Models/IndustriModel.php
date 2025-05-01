<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustriModel extends Model
{
    use HasFactory;
    protected $table = 'm_industri';
    protected $primaryKey = 'industri_id';
    public $timestamps = false;

    protected $fillable = ['industri_nama', 'kota_id', 'kategori_industri_id'];
}
