<?php

if (!function_exists('getCurrentSemester')) {
    function getCurrentSemester()
    {
        $now = Carbon\Carbon::now();
        $year = $now->year;
        
        // Semester Ganjil: September - Februari
        // Semester Genap: Maret - Agustus
        $month = $now->month;
        
        if ($month >= 3 && $month <= 8) {
            return $year . '2'; // Semester Genap
        } else {
            return ($month >= 9 ? $year : $year - 1) . '1'; // Semester Ganjil
        }
    }
} 