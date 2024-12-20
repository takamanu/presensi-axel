<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawai';

    public $timestamps = false;
    protected $fillable = [
        'id',
        'nip',
        'nama',
        'jenis_kelamin',
        'alamat',
        'no_handphone',
        'jabatan',
        'lokasi_presensi',
        'foto',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id_pegawai", "id");
    }

    public function pegawaiLokasi(): BelongsTo
    {
        return $this->belongsTo(LokasiPresensi::class, 'lokasi_presensi', 'nama_lokasi');
    }

    public function pegawaiJabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan', 'jabatan');
    }

    public function pegawaiPresensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'id_pegawai', 'id');
    }
}
