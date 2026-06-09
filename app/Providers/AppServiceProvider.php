<?php

namespace App\Providers;

use App\Contracts\ToastPaymentGateway;
use App\Http\Controllers\Customer\CartController;
use App\Services\AdminData;
use App\Services\SiteSettings;
use App\Services\Toast\LiveToastPaymentGateway;
use App\Services\Toast\MockToastPaymentGateway;
use App\Services\Toast\ToastConfiguration;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ToastPaymentGateway::class, function ($app) {
            return ToastConfiguration::isLive()
                ? $app->make(LiveToastPaymentGateway::class)
                : $app->make(MockToastPaymentGateway::class);
        });
    }

    public function boot(): void
    {
        View::composer('layouts.customer', function ($view) {
            $cart = CartController::resolveCart();
            $view->with('cartItems', $cart['cartItems']);
            $view->with('cartCount', $cart['cartCount']);
            $view->with('cartSubtotal', $cart['cartSubtotal']);
            $view->with('site', SiteSettings::all());
            $view->with('content', SiteSettings::blocks()->map(fn ($block) => $block->value)->all());
            $view->with('toastPayment', [
                'mode' => ToastConfiguration::mode(),
                'live' => ToastConfiguration::isLive(),
                'label' => ToastConfiguration::label(),
            ]);
        });

        View::composer('layouts.admin', function ($view) {
            $view->with('site', SiteSettings::all());

            if (! $view->offsetExists('badges')) {
                $view->with('badges', AdminData::getNavBadges());
            }
        });
    }
}
