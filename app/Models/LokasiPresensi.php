<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LokasiPresensi extends Model
{
    use HasFactory;

    protected $table = 'lokasi_presensi';

    protected $fillable = [
        'nama_lokasi',
        'alamat_lokasi',
        'tipe_lokasi',
        'latitude',
        'longitude',
        'radius',
        'zona_waktu',
        'jam_masuk',
        'jam_pulang'
    ];

    public function lokasiPegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class, 'nama_lokasi', 'nama_lokasi');
    }
}
