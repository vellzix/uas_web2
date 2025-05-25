<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'sks',
        'semester',
        'prodi_id',
        'deskripsi',
        'status',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function prerequisite()
    {
        return $this->belongsToMany(Matakuliah::class, 'matakuliah_prerequisite', 'matakuliah_id', 'prerequisite_id');
    }

    public function dosen() {
        return $this->belongsTo(Dosen::class);
    }

    public function krs() {
        return $this->hasMany(KRS::class);
    }

    public function nilai() {
        return $this->hasMany(Nilai::class);
    }
}
