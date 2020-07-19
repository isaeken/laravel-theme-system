<?php
/**
 * Laravel Theme System
 * @author İsa Eken <hello@isaeken.com.tr>
 * @license MIT
 */

namespace IsaEken\ThemeSystem\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ThemeSystem
 * @package IsaEken\ThemeSystem\Facades
 */
class ThemeSystem extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \IsaEken\ThemeSystem\ThemeSystem::class;
    }
}
