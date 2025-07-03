<?php

return [

    /* -------------------------------------------------
     | Informações básicas
     |--------------------------------------------------*/
    'name'            => env('APP_NAME', 'Laravel'),
    'env'             => env('APP_ENV', 'production'),
    'debug'           => (bool) env('APP_DEBUG', false),
    'url'             => env('APP_URL', 'http://localhost'),

    /* -------------------------------------------------
     | Localização / fuso
     |--------------------------------------------------*/
    'timezone'        => 'UTC',
    'locale'          => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale'    => env('APP_FAKER_LOCALE', 'en_US'),

    /* -------------------------------------------------
     | Criptografia
     |--------------------------------------------------*/
    'cipher'         => 'AES-256-CBC',
    'key'            => env('APP_KEY'),
    'previous_keys'  => array_filter(explode(',', env('APP_PREVIOUS_KEYS', ''))),

    /* -------------------------------------------------
     | Service Providers
     |--------------------------------------------------*/
    'providers' => [

        /*
        |--------------------------------------------------------------------------
        | Laravel Framework Service Providers
        |--------------------------------------------------------------------------
        */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,

        /*  ⚠️  Ordem correta: Translation → Validation → View  */
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
        |--------------------------------------------------------------------------
        | Pacotes de Terceiros
        |--------------------------------------------------------------------------
        */
        Spatie\Permission\PermissionServiceProvider::class,

        /*
        |--------------------------------------------------------------------------
        | Application Service Providers
        |--------------------------------------------------------------------------
        */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ],

    /* -------------------------------------------------
     | Modo de manutenção
     |--------------------------------------------------*/
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store'  => env('APP_MAINTENANCE_STORE',  'database'),
    ],
];
