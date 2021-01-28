<?php

namespace App\Adapters;

use App\Adapters\Interfaces\GeocodeInterface;
use App\Adapters\GeocodeAdapter;

class AdapterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            GeocodeInterface::class,
            GeocodeAdapter::class
        );
    }
}
?>