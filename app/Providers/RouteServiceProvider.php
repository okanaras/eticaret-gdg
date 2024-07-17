<?php

namespace App\Providers;

use App\Models\Discounts;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ! php artisan make:provider RouteServiceProvider ile bu provider imi biz olusturduk. Provider olusturulduktan sonra bootsrap/providers altina tanimlanmasi gerekli

        /** ROUTE BINDING */
        Route::bind('product', function ($value) { // Buradaki key'in (product), web.php de tanimlanan key ile ayni olmasi gerekli. $value ise gelen deger oluyor bu ornek icin: slug
            return Product::query()
                ->with(['productsMain', 'productsMain.category', 'productsMain.brand', 'variantImages', 'sizeStock'])
                ->whereHas('sizeStock', function ($q) {
                    $q->where('remaining_stock', '>', 0);
                })
                ->where('slug', $value)
                ->firstOrFail();
        });

        Route::bind('discount', function ($value) { // Buradaki key'in (discount), web.php de tanimlanan key ile ayni olmasi gerekli.
            return Discounts::query()->where('discounts.id', $value)->firstOrFail();
        });
    }
}