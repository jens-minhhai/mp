<?php

namespace App\Middleware;

class Request
{
    public function __invoke($request, $response, $next)
    {
        $domain = $request->getUri()->getBaseUrl();
        $info = $request->getAttributes()['routeInfo'];

        $app = container('app');
        $app['app'] = $this->getApp($domain);
        container('app', $app);

        return $next($request, $response);
    }

    private function getApp(string $domain = '')
    {
        $target = db()->select('id', 'app_id')
                        ->from('app_domain')
                        ->where('domain', $domain)
                        ->where('mode', ENABLE)
                        ->alive()
                        ->first();

        if ($target) {
            $app_id = $target['app_id'];

            return db()->select('id', 'title', 'token')
                        ->from('app')
                        ->where('id', $app_id)
                        ->where('mode', ENABLE)
                        ->alive()
                        ->first();
        }

        abort(400, 'invalid app');
    }
}
