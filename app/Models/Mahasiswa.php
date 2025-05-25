<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'prodi_id',
        'semester',
        'foto',
        'angkatan',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function krs()
    {
        return $this->hasMany(KRS::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function tugasMahasiswa()
    {
        return $this->hasMany(TugasMahasiswa::class);
    }
}
