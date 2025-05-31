<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;

    protected $table = 'krs';

    protected $fillable = [
        'mahasiswa_id',
        'jadwal_id',
        'periode_akademik_id',
        'status'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function periodeAkademik()
    {
        return $this->belongsTo(PeriodeAkademik::class);
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
