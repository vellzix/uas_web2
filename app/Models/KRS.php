<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KRS extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'jadwal_id',
        'semester',
        'tahun_ajaran',
        'status',
        'keterangan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function nilai()
    {
        return $this->hasOne(Nilai::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }
}
