<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'presensi';
    protected $fillable = [
        'id_pegawai',
        'tanggal_masuk',
        'jam_masuk',
        'foto_masuk',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id');
    }
}
