<?php

namespace App\Providers;

use App\Models\Prodi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share $prodis variable with specific views
        View::composer([
            'admin.partials.mahasiswa-modal',
            'admin.partials.dosen-modal',
            'admin.partials.matakuliah-modal'
        ], function ($view) {
            $view->with('prodis', Prodi::orderBy('nama')->get());
        });
    }
} 