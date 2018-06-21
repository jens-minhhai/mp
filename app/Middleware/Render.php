<?php
namespace App\Middleware;

use Addon;
use Lazy;
use Request;
use View;

class Render
{
    public function __invoke($request, $response, $next)
    {
        $business = $request->getAttribute('business');

        $type = $business['type'] ?? '';
        if ($type == 'template') {
            $content = $this->renderTemplate($business);
            $response->getBody()->write($content);

            return $response;
        }

        if ($type == 'json') {
            return $response->withJson($business['data']);
        }

        return $response;
    }

    protected function renderTemplate($business)
    {
        if ($business['template']) {
            $business['data'] = Lazy::load($business['data']);
            $app = array_get(container('app'), 'app');
            $data = array_merge($business['data'], ['meta' => $this->getMeta(), 'app' => $app]);

            return View::fs($business['template'], $data);
        }

        return '';
    }

    private function getMeta()
    {
        $app = container('app');
        $seo = $app['seo'];

        $app_title = array_get($app, 'app.title');

        if ($seo['title']) {
            $seo['title'] .= ' | ' . $app_title;
        } else {
            $seo['title'] = $app_title;
        }

        $request = $app['request'];
        list($controller, $action) = $request['path'];

        $tmp = array_get($app, 'config.asset');
        $asset = $tmp['common'];

        $tmp = array_get($tmp, "{$controller}.{$action}", []);
        if ($tmp) {
            $asset = array_merge_recursive($asset, $tmp);
        }

        $prefix = array_get($app, 'config.app.host.asset') . '/';
        if (Request::get('channel_code')) {
            $prefix .= Request::get('channel_code') . '/';
        }

        foreach ($asset as $type => &$list) {
            foreach ($list as &$item) {
                if (strpos($item, 'http') === 0) {
                    continue;
                }
                if (strpos($item, 'vendor/') === 0) {
                    $item = $prefix . $item;
                    continue;
                }
                $item = $prefix . $type . '/' . $item;
            }
        }

        return compact('asset', 'seo');
    }
}
