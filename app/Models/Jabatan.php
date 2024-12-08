<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = 'jabatan';
    protected $fillable = [
        'jabatan',
    ];

    public function jabatanPegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class, 'jabatan', 'jabatan');
    }
}
