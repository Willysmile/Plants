<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\ReppotingHistory;

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
        // Route model binding for repotting history
        // The route parameter is 'repotting_history' but the model is 'ReppotingHistory'
        Route::model('repotting_history', ReppotingHistory::class);
    }
}
