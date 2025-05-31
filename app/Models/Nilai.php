<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilais';

    protected $fillable = [
        'krs_id',
        'nilai_uts',
        'nilai_uas',
        'nilai_tugas',
        'nilai_angka',
        'nilai_huruf',
        'nilai_indeks',
        'keterangan'
    ];

    protected $casts = [
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_tugas' => 'decimal:2',
        'nilai_angka' => 'decimal:2',
        'nilai_indeks' => 'decimal:2'
    ];

    public function krs()
    {
        return $this->belongsTo(KRS::class, 'krs_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public static function hitungNilaiHuruf($nilai_angka)
    {
        if ($nilai_angka >= 85) {
            return ['huruf' => 'A', 'indeks' => 4.00];
        } elseif ($nilai_angka >= 80) {
            return ['huruf' => 'A-', 'indeks' => 3.70];
        } elseif ($nilai_angka >= 75) {
            return ['huruf' => 'B+', 'indeks' => 3.30];
        } elseif ($nilai_angka >= 70) {
            return ['huruf' => 'B', 'indeks' => 3.00];
        } elseif ($nilai_angka >= 65) {
            return ['huruf' => 'B-', 'indeks' => 2.70];
        } elseif ($nilai_angka >= 60) {
            return ['huruf' => 'C+', 'indeks' => 2.30];
        } elseif ($nilai_angka >= 55) {
            return ['huruf' => 'C', 'indeks' => 2.00];
        } elseif ($nilai_angka >= 40) {
            return ['huruf' => 'D', 'indeks' => 1.00];
        } else {
            return ['huruf' => 'E', 'indeks' => 0.00];
        }
    }
}
