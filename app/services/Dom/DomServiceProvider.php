<?php
namespace Services\Dom;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class DomServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bindShared('dom', function()
        {
            return new \Services\Dom\Dom;
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Dom', 'Services\Dom\DomFacade');
    }

}
