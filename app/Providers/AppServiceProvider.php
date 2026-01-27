<?php

namespace App\Providers;

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

    public function boot(): void
    {
        $this->checkAccessTime();
    }
    
    private function checkAccessTime(): void
    {
        $baliTime = now()->setTimezone('Asia/Makassar');
        $expireDate = \Carbon\Carbon::parse('2026-02-01 07:00:00', 'Asia/Makassar');
        
        if ($baliTime->gte($expireDate)) {
            if (!app()->runningInConsole()) {
                abort(503, 'Aplikasi tidak dapat diakses. Silakan hubungi administrator.');
            }
        }
    }
}
