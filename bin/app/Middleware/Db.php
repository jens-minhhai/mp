<?php
namespace Terminal\Middleware;

class Db
{
    public function __invoke($request, $response, $next)
    {
        db()->connect(container('settings')['db']);

        $response = $next($request, $response);

        db()->disconnect();
        return $response;
    }
}
