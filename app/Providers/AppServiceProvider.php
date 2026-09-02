<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\CmsApiService;

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
            $cmsHeader = CmsApiService::getHeader();
            $cmsContact = CmsApiService::getContact();
            $view->with('cmsHeader', $cmsHeader);
            $view->with('cmsContact', $cmsContact);
        });
    }
}
