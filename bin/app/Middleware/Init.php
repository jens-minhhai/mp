<?php

namespace Terminal\Middleware;

use Kernel\Factory;

class Init
{
    public function __invoke($request, $response, $next)
    {
        return $next($request, $response);
    }
}
