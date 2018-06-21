<?php

namespace Terminal\Middleware;

use Symfony\Component\Console\Application;
use Config;

class Console
{
    public function __invoke($request, $response, $next)
    {
        $terminal = new Application();
        $terminal->setAutoExit(false);

        $commands = Config::read('command');
        foreach ($commands as $class) {
            $terminal->add(new $class);
        }

        $terminal->run();

        return $next($request, $response);
    }
}
