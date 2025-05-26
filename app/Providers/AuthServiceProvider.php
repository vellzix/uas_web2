<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register admin role gate
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        // Register dosen role gate
        Gate::define('dosen', function ($user) {
            return $user->role === 'dosen';
        });

        // Register mahasiswa role gate
        Gate::define('mahasiswa', function ($user) {
            return $user->role === 'mahasiswa';
        });
    }
} 