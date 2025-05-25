<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'matakuliah_id',
        'dosen_id',
        'semester',
        'tahun_ajaran',
        'kelas',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'kapasitas',
        'status',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
    ];

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function krs()
    {
        return $this->hasMany(KRS::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
}
