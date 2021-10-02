<?php

namespace IsaEken\ThemeSystem\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;

class ThemeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  $theme
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $theme): mixed
    {
        theme_system()->setTheme($theme);

        return $next($request);
    }
}
