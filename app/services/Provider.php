<?php
namespace Services;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class Provider extends ServiceProvider {

    public function register()
    {
        $this->app->bindShared('basic', function()
        {
            return new \Services\Basic\Basic;
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Basic', 'Services\Basic\BasicFacade');


        $this->app->bindShared('dom', function()
        {
            return new \Services\Dom\Dom;
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Dom', 'Services\Dom\DomFacade');

        $this->app->bindShared('http', function()
        {
            return new \Services\Http\Http;
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Http', 'Services\Http\HttpFacade');
    }

}
