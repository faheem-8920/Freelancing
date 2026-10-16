<?php

namespace App\Providers;





use App\Models\Cart;
use Illuminate\Support\Facades\View;
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
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartCount = 0;

            if (auth()->check()) {
                $cart = Cart::where('user_id', auth()->id())
                    ->withSum('items', 'quantity')
                    ->first();

                $cartCount = (int) ($cart?->items_sum_quantity ?? 0);
            } else {
                $token = request()->cookie('cart_token');

                if ($token) {
                    $cart = Cart::where('token', $token)
                        ->whereNull('user_id')
                        ->withSum('items', 'quantity')
                        ->first();

                    $cartCount = (int) ($cart?->items_sum_quantity ?? 0);
                }
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
