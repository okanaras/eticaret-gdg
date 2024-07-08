<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Events\UserRegisterEvent;
use Illuminate\Pagination\Paginator;
use App\Listeners\UserRegisterListener;
use App\Services\BrandService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegisterEvent::class => [
            UserRegisterListener::class,
        ],
    ];

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
        /**
         * user modeline asagidaki kodu yapistirmistik.
         * #[ObservedBy([UserObserver::class])]
         *
         * yukardakini kaldirip asagidaki kodu buraya yapistirsak da ayni islevi gorecekti.
         *
         ** User::observe(UserObserver::class);
         */

        Paginator::useBootstrapFive();

        $brandService = $this->app->make(BrandService::class);
        $brandsColumns = $brandService->getAllActive();

        view()->composer('front.*', function ($view) use ($brandsColumns) {
            $view->with('brandsColumns', $brandsColumns);
        });
        // view('front.*')->share('brandsColumns', $brandsColumns); // share butun sayfalarla paylasmis olacak. Custom sayfalar icin composer kullandik.
    }
}