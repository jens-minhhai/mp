<?php

namespace App\Middleware;

use Kernel\Factory;
use Request;
use Log;

class Crsf
{
    public function __invoke($request, $response, $next)
    {
        $flag = $this->checkCsrf($request);

        if ($flag) {
            $csrf_token = $this->getActiveCrsf();

            $response = $response->withHeader('Mp-Csrf-Token', $csrf_token);
        } else {
            abort(400, 'Invalid or timout token');
        }

        return $next($request, $response);
    }

    protected function checkCsrf($request)
    {
        return container('csrf')->verify($request);
    }

    protected function getActiveCrsf()
    {
        return container('csrf')->active();
    }
}
