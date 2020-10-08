<?php

namespace Raultm\Pruebas\Facades;

use Illuminate\Support\Facades\Facade;

class Pruebas extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pruebas';
    }
}
