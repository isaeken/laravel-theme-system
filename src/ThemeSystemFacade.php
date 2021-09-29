<?php

namespace IsaEken\ThemeSystem;

use Illuminate\Support\Facades\Facade;

/**
 * @see \IsaEken\ThemeSystem\ThemeSystem
 */
class ThemeSystemFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'theme-system';
    }
}
