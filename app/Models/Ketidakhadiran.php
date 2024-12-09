<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ketidakhadiran extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'ketidakhadiran';
    protected $fillable = [
        'id_pegawai',
        'keterangan',
        'tanggal',
        'deskripsi',
        'file',
        'status_pengajuan',
    ];
}
