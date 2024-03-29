<?php

declare(strict_types=1);

namespace Cortex\Oauth\Providers;

use Cortex\Oauth\Models\Client;
use Cortex\Oauth\Models\AuthCode;
use Cortex\Oauth\Models\AccessToken;
use Cortex\Oauth\Models\RefreshToken;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;

class OAuthServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register(): void
    {
        // Bind eloquent models to IoC container
        $this->registerModels([
            'rinvex.oauth.client' => Client::class,
            'rinvex.oauth.auth_code' => AuthCode::class,
            'rinvex.oauth.access_token' => AccessToken::class,
            'rinvex.oauth.refresh_token' => RefreshToken::class,
        ]);
    }
}
