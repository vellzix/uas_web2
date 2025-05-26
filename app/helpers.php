<?php

use Carbon\Carbon;

if (!function_exists('getCurrentSemester')) {
    function getCurrentSemester()
    {
        $now = Carbon::now();
        $month = $now->month;
        
        // Semester Ganjil: September - Januari
        // Semester Genap: Februari - Juni
        // Semester Pendek: Juli - Agustus
        
        if ($month >= 9 || $month <= 1) {
            return 'Ganjil';
        } elseif ($month >= 2 && $month <= 6) {
            return 'Genap';
        } else {
            return 'Pendek';
        }
    }
}

if (!function_exists('getCurrentTahunAkademik')) {
    function getCurrentTahunAkademik()
    {
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;
        
        // Tahun akademik baru dimulai pada bulan September
        if ($month >= 9) {
            return $year . '/' . ($year + 1);
        } else {
            return ($year - 1) . '/' . $year;
        }
    }
} 