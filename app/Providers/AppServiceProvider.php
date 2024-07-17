<?php

namespace App\Providers;

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
        /** OBSERVER */
        /**
         * user modeline asagidaki kodu yapistirmistik.
         * #[ObservedBy([UserObserver::class])]
         *
         * yukardakini kaldirip asagidaki kodu buraya yapistirsak da ayni islevi gorecekti.
         *
         ** User::observe(UserObserver::class);
         */


        /** PAGINATION */
        Paginator::useBootstrapFive();
        // Paginator::defaultView('pagination::front-custom');


        /** VIEW COMPOSER & SHARE */
        $brandService = $this->app->make(BrandService::class);
        $brandsColumns = $brandService->getAllActive();

        // view('front.*')->share('brandsColumns', $brandsColumns); // share butun sayfalarla paylasmis olacak. Custom sayfalar icin composer kullandik.
        view()->composer('front.*', function ($view) use ($brandsColumns) {
            $view->with('brandsColumns', $brandsColumns);
        });
    }
}