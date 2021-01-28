<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Blade;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
        
        // setlocale(LC_ALL, 'id_ID.utf8');
        // Carbon::setLocale('id_ID.utf8');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Blade::directive('rupiah', function ($amount) {
            return "<?php echo 'Rp '.number_format($amount, 2); ?>";
        });        
    }
}
