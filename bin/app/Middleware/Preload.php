<?php

namespace Terminal\Middleware;

class Preload
{
    public function __invoke($request, $response, $next)
    {
        $this->boot();

        return $next($request, $response);
    }

    private function boot()
    {
        $config = $this->loadConfig();

        $this->loadFacade($config);

        container('app', compact('config'));
    }

    private function loadConfig()
    {
        $root = 'config/';
        $files = load($root . 'bootstrap.php', BIN);

        return $this->extendConfig([], $files, $root);
    }

    private function extendConfig(array $config, array $files, string $root)
    {
        foreach ($files as $name => $file) {
            $file = $root . $file;

            if (exist($file, BIN)) {
                $config = array_insert($config, $name, load($file, BIN));
            }
        }

        return $config;
    }

    private function loadFacade(array $config = [])
    {
        foreach ($config['facade'] as $alias => $class) {
            if (class_alias($class, $alias)) {
                continue;
            }
            abort(500, 'Loading facade fail');
        }
    }
}
