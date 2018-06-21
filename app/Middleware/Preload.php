<?php

namespace App\Middleware;

use Kernel\Factory;

class Preload
{
    public function __invoke($request, $response, $next)
    {
        $this->boot();
        $response = $next($request, $response);
        $this->finalize();

        return $response;
    }

    private function boot()
    {
        $app = container('app');

        $this->session($app);
        $config = $this->loadConfig($app);

        $app = $this->checkLocale($app, $config);

        $msg = $this->loadMessage($app);
        $this->loadFacade($config);
        $this->loadCsrf($app);

        container('app', array_merge($app, compact('config', 'msg')));
    }

    private function session(array $app)
    {
        $config = container('settings')['session'];

        $driver = env('APP_SESSION_DRIVER');
        $config = $config[$driver];

        $config['name'] = $app['app']['token'];
        $config['save_path'] .= $app['request']['channel_code'];

        $driver = 'Kernel\Session\\' . ucfirst($driver);
        container('session', Factory::instant($driver, $config));
    }

    private function finalize()
    {
        container('csrf')->finalize();
        container('flash')->finalize();
        container('session')->finalize();
    }

    private function loadConfig(array $app)
    {
        $channel_code = array_get($app, 'request.channel_code', '');
        $locale_code = array_get($app, 'request.locale_code', '');

        $root = 'config/' . $channel_code. '/';
        $config = $this->extendConfig([], load($root . 'bootstrap.php'), $root);

        if (!$locale_code) {
            $locale_code = key(array_get($config, 'app.locale'));
        }

        $controller = $app['request']['path'][0];
        $files = [
            $controller => $locale_code . '/' . str_replace('.', '/', $controller) . '.php'
        ];

        $files = array_merge($files, load("{$root}bootstrap.{$locale_code}.php"));

        $config = $this->extendConfig($config, $files, $root);

        $pref = $this->getPref($app);
        foreach ($pref as $item) {
            $config = array_insert($config, $item['code'], $item['value']);
        }

        return $config;
    }

    private function extendConfig(array $config, array $files, string $root)
    {
        foreach ($files as $name => $file) {
            $file = $root . $file;

            if (exist($file)) {
                $config = array_insert($config, $name, load($file));
            }
        }

        return $config;
    }

    private function loadFacade(array $config)
    {
        foreach ($config['facade'] as $alias => $class) {
            if (class_alias($class, $alias)) {
                continue;
            }
            abort(500, 'Loading facade fail');
        }
    }

    private function checkLocale(array $app, array $config)
    {
        $locale_list = array_get($config, 'app.locale', []);
        $locale_code = array_get($app, 'request.locale_code', '');
        if (!$locale_code) {
            $locale_code = key($locale_list);
        }

        $app['request']['locale'] = $locale_list[$locale_code] ?? 0;
        $app['request']['locale_code'] = $locale_code;

        return $app;
    }

    private function loadCsrf(array $app)
    {
        $token = array_get($app, 'app.api', 'mp');
        container('csrf')->enable($token);
    }

    private function loadMessage(array $app)
    {
        $app_id = array_get($app, 'app.id');
        $channel_id = array_get($app, 'request.channel');
        $locale_id = array_get($app, 'request.locale');

        $msg = db()->select('code', 'value')
                        ->from('message')
                        ->where('app_id', $app_id)
                        ->where('channel_id', $channel_id)
                        ->where('locale_id', $locale_id)
                        ->where('mode', ENABLE)
                        ->get();

        return array_pluck($msg, '{n}.code', '{n}.value');
    }

    private function getPref(array $app)
    {
        $app_id = array_get($app, 'app.id');
        $channel_id = array_get($app, 'request.channel');

        return db()->select('code', 'value')
                        ->from('config')
                        ->where('app_id', $app_id)
                        ->where('channel_id', $channel_id)
                        ->where('mode', ENABLE)
                        ->get();
    }
}
