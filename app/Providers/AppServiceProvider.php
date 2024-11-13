<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
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
    public function boot()
    {
        if ($this->app->environment('production')) {
            // Force HTTPS routes
            URL::forceScheme('https');
        }
        
        Blade::directive('markdown', function ($expression) {
            return "<?php echo (new Parsedown)->text($expression); ?>";
        });
    }
}
