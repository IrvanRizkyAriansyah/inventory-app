<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
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
        //
        Gate::define('filament-access', function ($user, $permission) {
            return match ($user->role) {
                'admin' => true,

                'manager' => in_array($permission, [
                    'view_dashboard',
                    'view_penerimaan',
                    'view_pengeluaran',
                    'view_stok_opname',
                    'view_laporan',
                    'view_master',
                ]),

                'finance' => in_array($permission, [
                    'view_dashboard',
                    'view_laporan',
                    'view_penerimaan',
                    'view_pengeluaran',
                ]),

                'purchasing' => in_array($permission, [
                    'view_dashboard',
                    'create_penerimaan',
                    'view_penerimaan',
                    'view_pengeluaran',
                    'edit_penerimaan',
                    'create_pengeluaran',
                    'edit_pengeluaran',
                    'view_stok_opname',
                    'edit_stok_opname',
                    'create_stok_opname',
                    'delete_stok_opname',
                    'view_master',
                    'view_laporan',
                ]),

                default => false,
            };
        });

    }
}
