<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketidakhadiran extends Model
{
    use HasFactory;
    protected $table = 'ketidakhadiran';
    public $timestamps = false;

    protected $fillable = [
        'id_pegawai',
        'keterangan',
        'tanggal',
        'deskripsi',
        'file',
        'status_pengajuan'
    ];
}
