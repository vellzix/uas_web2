<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PeriodeAkademik extends Model
{
    use HasFactory;

    protected $table = 'periode_akademik';

    protected $fillable = [
        'nama',
        'tahun_akademik',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'krs_mulai',
        'krs_selesai',
        'is_active'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'krs_mulai' => 'datetime',
        'krs_selesai' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function jadwalMatakuliah()
    {
        return $this->hasMany(JadwalMatakuliah::class);
    }

    public function krs()
    {
        return $this->hasMany(KRS::class);
    }

    public function isKRSPeriodActive()
    {
        $now = Carbon::now();
        return $this->is_active && 
               $now->between($this->krs_mulai, $this->krs_selesai);
    }
} 