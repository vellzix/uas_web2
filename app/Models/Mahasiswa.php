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
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'prodi_id',
        'angkatan',
        'foto',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date'
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

    public function calculateIPK()
    {
        $nilais = $this->krs()
            ->join('nilais', 'krs.id', '=', 'nilais.krs_id')
            ->join('jadwal', 'krs.jadwal_id', '=', 'jadwal.id')
            ->join('matakuliahs', 'jadwal.matakuliah_id', '=', 'matakuliahs.id')
            ->select('nilais.nilai_angka', 'matakuliahs.sks')
            ->get();

        if ($nilais->isEmpty()) {
            return 0;
        }

        $totalBobot = 0;
        $totalSKS = 0;

        foreach ($nilais as $nilai) {
            $bobot = $this->getNilaiBobot($nilai->nilai_angka);
            $totalBobot += ($bobot * $nilai->sks);
            $totalSKS += $nilai->sks;
        }

        return $totalSKS > 0 ? round($totalBobot / $totalSKS, 2) : 0;
    }

    public function calculateTotalSKS()
    {
        return $this->krs()
            ->join('jadwal', 'krs.jadwal_id', '=', 'jadwal.id')
            ->join('matakuliahs', 'jadwal.matakuliah_id', '=', 'matakuliahs.id')
            ->join('nilais', 'krs.id', '=', 'nilais.krs_id')
            ->where('nilais.nilai_angka', '>=', 60) // Hanya SKS yang lulus
            ->sum('matakuliahs.sks');
    }

    private function getNilaiBobot($nilai_angka)
    {
        if ($nilai_angka >= 85) return 4;
        if ($nilai_angka >= 80) return 3.7;
        if ($nilai_angka >= 75) return 3.3;
        if ($nilai_angka >= 70) return 3;
        if ($nilai_angka >= 65) return 2.7;
        if ($nilai_angka >= 60) return 2.3;
        if ($nilai_angka >= 55) return 2;
        if ($nilai_angka >= 50) return 1.7;
        if ($nilai_angka >= 45) return 1.3;
        if ($nilai_angka >= 40) return 1;
        return 0;
    }
}
