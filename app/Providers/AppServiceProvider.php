<?php

namespace App\Providers;

use App\Contracts\ProductRepository;
use App\Services\CartService;
use App\Services\ConfigProductRepository;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ProductRepository::class, ConfigProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(
            ['components.cart.button', 'components.cart.drawer'],
            fn (ViewContract $view) => $view->with('cart', $this->app->make(CartService::class)->summary()),
        );
    }
}
