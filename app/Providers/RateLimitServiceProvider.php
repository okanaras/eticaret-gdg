<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitServiceProvider extends ServiceProvider
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
        /**
         *
         ** RateLimit herhangi bir url e gelen istege limit     koyabiliriz. Ornek 60 dakika da yalnizca 10 istek gelebilsin. Boot ta tanimli olamsi projenin daha duzgun sekilde calismasini saglar.
         *
         */

        RateLimiter::for('registration', function ($job) {
            return Limit::perHour(500)->by($job->ip()); // 5 requests per hour by IP address
        });

        RateLimiter::for('login', function ($job) {
            return Limit::perHour(10)->by($job->ip()); // 10 requests per hour by IP address
        });

        RateLimiter::for('high-traffic-page', function ($job) {
            return Limit::perHour(100)->by($job->ip()); // 100 requests per hour by IP address
        });
    }
}