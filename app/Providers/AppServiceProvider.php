<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.navigation', function ($view) {
            $cartCount = 0;

            if (auth()->check()) {
                $cartCount = auth()->user()
                    ->cart()
                    ->withSum('items', 'quantity')
                    ->first()
                    ?->items_sum_quantity ?? 0;
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
