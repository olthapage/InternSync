<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MagangModel;

class LogHarianModel extends Model
{
    use HasFactory;

    protected $table = 'm_logharian';

    protected $primaryKey = 'logHarian_id';

    protected $fillable = [
        'mahasiswa_magang_id',
        'tanggal',
    ];

    public function mahasiswaMagang()
    {
        return $this->belongsTo(MagangModel::class, 'mahasiswa_magang_id', 'mahasiswa_magang_id');
    }
    public function detail()
    {
        return $this->hasMany(LogHarianDetailModel::class, 'logHarian_id', 'logHarian_id');
    }
}
