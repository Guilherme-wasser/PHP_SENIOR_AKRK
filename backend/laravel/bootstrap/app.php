<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Bootstrap the application
|--------------------------------------------------------------------------
|
| A partir do Laravel 11 o Kernel deixou de existir.  Toda a configuração
| de rotas, middlewares e tratamento de exceções é feita neste arquivo.
|
*/

return Application::configure(
        basePath: dirname(__DIR__),   //  < raiz do projeto
    )
    /* -----------------------------------------------------------------
     | Rotas
     |------------------------------------------------------------------*/
    ->withRouting(
        web:      __DIR__.'/../routes/web.php',
        api:      __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health:   '/up',
    )

    /* -----------------------------------------------------------------
     | Middlewares
     |------------------------------------------------------------------*/
    ->withMiddleware(function (Middleware $mw) {

        /* ----------  middleware GLOBAIS (toda requisição)  ---------- */
        $mw->use([
            \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        /* ----------  grupo WEB  (views, sessão etc.)  ---------- */
        $mw->appendToGroup('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        /* ----------  grupo API  ---------- */
        $mw->appendToGroup('api', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        /* ----------  aliases (apelidos)  ---------- */
        $mw->alias([
            // Laravel nativos
            'auth'               => \App\Http\Middleware\Authenticate::class,
            'auth.basic'         => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session'       => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers'      => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'                => \Illuminate\Auth\Middleware\Authorize::class,
            'password.confirm'   => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed'             => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle'           => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'           => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            // JWT-Auth
            'jwt.auth'           => \PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate::class,
            'jwt.refresh'        => \PHPOpenSourceSaver\JWTAuth\Http\Middleware\RefreshToken::class,

            // Spatie Permission
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

            // ---  SEU ALIAS PERSONALIZADO ---
            'admin'              => AdminMiddleware::class,
        ]);
    })

    /* -----------------------------------------------------------------
     | Tratamento de exceções (deixe como estiver por enquanto)
     |------------------------------------------------------------------*/
    ->withExceptions(fn (Exceptions $e) => null)

    /* -----------------------------------------------------------------
     | Cria e devolve a instância da aplicação
     |------------------------------------------------------------------*/
    ->create();
