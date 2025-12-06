<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cookie;

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
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        if (!auth()->check()) {
            // Check if the guest user ID is not already stored in the cookie
            if (!Cookie::has('guest_user_id')) {
                // Generate a unique ID for the guest user
                $guestUserId = uniqid('guest_', true);
                
                // Store the guest user ID in the cookie for 30 days 
                Cookie::queue('guest_user_id', $guestUserId, 60 * 24 * 30); // 30 days
            }
        }
    }
}
