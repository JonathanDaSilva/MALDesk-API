<?php
namespace Services;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class Provider extends ServiceProvider {

    public function register()
    {
        $loader = AliasLoader::getInstance();

        // Basic
        $this->app->bindShared('basic', function()
        {
            return new \Services\Basic\Basic;
        });
        $loader->alias('Basic', 'Services\Basic\BasicFacade');

        // Dom
        $this->app->bindShared('anime', function()
        {
            return new \Services\Parser\Anime;
        });
        $loader->alias('Anime', 'Services\Parser\AnimeFacade');

        // Dom
        $this->app->bindShared('dom', function()
        {
            return new \Services\Dom\Dom;
        });
        $loader->alias('Dom', 'Services\Dom\DomFacade');

        // Http
        $this->app->bindShared('http', function()
        {
            return new \Services\Http\Http;
        });
        $loader->alias('Http', 'Services\Http\HttpFacade');
    }

}
