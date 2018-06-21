<?php

namespace App\Middleware;

use Kernel\Factory;

class Init
{
    public function __invoke($request, $response, $next)
    {
        $req = $this->req($request);
        // print('<pre>');
        // print_r($req);
        // print('</pre>');
        // exit;
        $settings = container('settings');
        container('app', ['request' => $req]);

        return $next($request, $response);
    }

    private function req($request)
    {
        $uri = $request->getUri();
        $scheme = $uri->getScheme();
        $host = $uri->getHost();

        $info = $request->getAttributes()['routeInfo'];
        // print('<pre>');
        // print_r($info);
        // print('</pre>');
        // exit;
        extract($info[2]);

        $channel_code = empty($channel) ? env('APP_DEFAULT_CHANNEL') : $channel;
        $channel = constant('CHANNEL_' . strtoupper($channel_code));

        $locale = '';
        $locale_code = $locale ?? env('APP_DEFAULT_LOCALE');

        $query = $request->getParams();
        $req = array_shift($query);

        $host_full = sprintf('%s://%s', $scheme, $host);

        $base = '';
        if ($channel !== CHANNEL_ARENA) {
            $base = '/' . $channel_code;
        }
        $base = $host_full . $base . '/' . $locale_code;

        return [
            'channel' => $channel,
            'channel_code' => $channel_code,
            'locale' => $locale,
            'locale_code' => $locale_code,
            'method' => strtolower($request->getMethod()),
            'query' => $query,
            'input' => $request->getParsedBody() ?? [],
            'scheme' => $scheme,
            'host' => $host,
            'host_full' => $host_full,
            'base_url' => trim($base, '/'),
            'uri' => $url ?? '',
            'uri_full' => $req
        ];
    }

    private function log(array $req, array $config)
    {
        $config = $config['stream'];
        $config['path'] = $config['path'] . '/' . $req['channel_code'];

        container('log', Factory::instant('Kernel\Log\Stream', $config));
    }
}
