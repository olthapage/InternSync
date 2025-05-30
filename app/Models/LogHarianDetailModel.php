<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogHarianDetailModel extends Model
{
    use HasFactory;

    protected $table = 'm_logharian_detail';

    protected $primaryKey = 'logHarianDetail_id';

    protected $fillable = [
        'logHarian_id',
        'isi',              
        'lokasi',
        'tanggal_kegiatan',
        'status_approval_dosen',
        'status_approval_industri',
        'catatan_dosen',
        'catatan_industri',
    ];

    public function logHarian()
{
    return $this->belongsTo(LogHarianModel::class, 'logHarian_id', 'logHarian_id');
}
}
