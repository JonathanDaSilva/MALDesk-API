<?php
namespace Services\Basic;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Service\Basic\Basic
 */
class BasicFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'basic';
    }
}
