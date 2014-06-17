<?php
namespace Services\Parser;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Services\Parser\Anime
 */
class AnimeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'anime';
    }
}
