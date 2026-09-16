<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        // Batasi percobaan login: maksimal 5 kali per menit per kombinasi username & IP
        RateLimiter::for('login', function (Request $request) {
            $key = strtolower((string) $request->input('username')).'|'.$request->ip();

            return [
                Limit::perMinute(5)->by($key)->response(function (Request $request) use ($key) {
                    $seconds = RateLimiter::availableIn('login:'.$key);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
                        ], 429);
                    }

                    return redirect()
                        ->route('login')
                        ->withInput()
                        ->withErrors(['username' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."]);
                }),
            ];
        });
    }
}
