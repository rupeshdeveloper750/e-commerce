<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define dynamic gates if permissions table exists
        try {
            if (Schema::hasTable('permissions')) {
                Gate::before(function ($user, $ability) {
                    if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                        return true;
                    }
                });

                foreach (\App\Models\Permission::all() as $permission) {
                    Gate::define($permission->slug, function ($user) use ($permission) {
                        return method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo($permission);
                    });
                }
            }
        } catch (\Exception $e) {
            // Avoid failing when migrations haven't run or during setup
        }
    }
}
