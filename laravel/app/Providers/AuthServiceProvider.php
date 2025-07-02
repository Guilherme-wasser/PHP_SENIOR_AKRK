<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapeamento de policies da aplicação.
     *
     * Se precisar de policies no futuro, registre aqui:
     *  'App\Models\Post' => 'App\Policies\PostPolicy',
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Bootstrap any authentication / authorization services.
     */
    public function boot(): void
    {
        // carrega o array $policies acima
        $this->registerPolicies();

        /**
         * Se o usuário tiver o papel “admin” (Spatie), concede
         * acesso a qualquer Gate/Policy automaticamente.
         */
        Gate::before(function ($user, string $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
