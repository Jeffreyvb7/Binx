<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;


class BinxRouteServiceProvider extends RouteServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    public function register()
    {
        parent::register();
    }

    public function map()
    {

        parent::map();

        $this->mapRouteBinds();

        $this->mapChatRoutes();

        $this->mapPortfolioRoutes();
    }

    /**
     * Custom chat routes
     *
     * @return void
     */
    protected function mapChatRoutes()
    {
        Route::prefix('chat')
            ->middleware('web', 'auth')
            ->namespace($this->namespace)
            ->group(base_path('routes/chat.php'));
    }

    /**
     * Custom portfolio routes
     *
     * @return void
     */
    protected function mapPortfolioRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/portfolio.php'));
    }

    /**
     * Custom route binds
     *
     * @return void
     */
    protected function mapRouteBinds()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/binds.php'));
    }
}
