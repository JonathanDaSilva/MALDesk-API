<?php
namespace Services\Http;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class HttpServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bindShared('http', function()
        {
            return new \Services\Http\Http;
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Http', 'Services\Http\HttpFacade');
    }

}
