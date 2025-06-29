<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route; // <-- não esqueça de importar Route!

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
        // 🔗 Registra suas rotas API com middleware e prefixo
        Route::middleware('api')
             ->prefix('api')
             ->group(base_path('routes/api.php'));
    }
}
