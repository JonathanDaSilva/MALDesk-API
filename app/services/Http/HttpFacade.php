<?php
namespace Services\Http;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Service\Http\Http
 */
class HttpFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'http';
    }
}
