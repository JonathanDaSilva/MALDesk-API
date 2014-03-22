<?php
namespace Services\Basic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class BasicServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bindShared('basic', function()
        {
            return new \Services\Basic\Basic;
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Basic', 'Services\Basic\BasicFacade');
    }

}
