<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasMahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'tugas_id',
        'mahasiswa_id',
        'file_path',
        'nilai',
        'komentar',
        'status',
        'waktu_pengumpulan',
    ];

    protected $casts = [
        'waktu_pengumpulan' => 'datetime',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
} 