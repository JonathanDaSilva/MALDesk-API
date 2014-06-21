<?php
namespace Services\Convert;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Services\Convert\Convert
 */
class ConvertFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'convert';
    }
}
