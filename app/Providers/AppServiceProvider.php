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
                    // Support both web guard users and admin guard users
                    if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                        return true;
                    }
                });

                // Also gate-check using admin guard user when web user is null
                Gate::guessPolicyNamesUsing(function ($modelClass) {
                    return null; // No policies, only gates
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

        // Share footer categories and settings globally
        view()->composer('*', function ($view) {
            try {
                if (Schema::hasTable('categories')) {
                    $footerCategories = \App\Models\Category::where('status', true)->whereNull('parent_id')->orderBy('sort_order')->take(6)->get();
                } else {
                    $footerCategories = collect();
                }
                
                if (Schema::hasTable('site_settings')) {
                    $siteSettings = \App\Models\SiteSetting::pluck('value', 'key');
                } else {
                    $siteSettings = collect([
                        'brand_description' => 'Curators of premium quiet luxury apparel, accessories, and structural lifestyle collectibles.',
                        'contact_email' => 'concierge@shopme.com',
                        'contact_phone' => '+1 (800) 555-0199',
                        'contact_address' => '100 Quiet Luxury Way, Suite 400, Milan, Italy',
                        'business_hours' => 'Mon - Fri: 9:00 AM - 6:00 PM CET',
                        'instagram_url' => 'https://instagram.com/shopme',
                        'facebook_url' => 'https://facebook.com/shopme',
                        'pinterest_url' => 'https://pinterest.com/shopme',
                    ]);
                }
                
                $view->with(compact('footerCategories', 'siteSettings'));
            } catch (\Exception $e) {
                $view->with([
                    'footerCategories' => collect(),
                    'siteSettings' => collect([
                        'brand_description' => 'Curators of premium quiet luxury apparel, accessories, and structural lifestyle collectibles.',
                        'contact_email' => 'concierge@shopme.com',
                        'contact_phone' => '+1 (800) 555-0199',
                        'contact_address' => '100 Quiet Luxury Way, Suite 400, Milan, Italy',
                        'business_hours' => 'Mon - Fri: 9:00 AM - 6:00 PM CET',
                        'instagram_url' => 'https://instagram.com/shopme',
                        'facebook_url' => 'https://facebook.com/shopme',
                        'pinterest_url' => 'https://pinterest.com/shopme',
                    ])
                ]);
            }
        });
    }
}
