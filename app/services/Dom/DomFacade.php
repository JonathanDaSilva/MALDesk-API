<?php
namespace Services\Dom;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Service\Dom\Dom
 */
class DomFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dom';
    }
}
