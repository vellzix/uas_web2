<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_id',
        'judul',
        'deskripsi',
        'deadline',
        'bobot',
        'status',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function pengumpulan()
    {
        return $this->hasMany(TugasMahasiswa::class);
    }
} 