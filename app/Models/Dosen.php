<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'prodi_id',
        'bidang',
        'foto',
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

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function krs()
    {
        return $this->hasManyThrough(KRS::class, Jadwal::class);
    }
}
