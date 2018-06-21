<?php

    $container = $app->getContainer();

    $container['db'] = function ($container) {
        return new Kernel\Db\MySql();
    };

    $container['csrf'] = function ($container) {
        $session = $container['session'];

        return new Kernel\Csrf($session);
    };

    $container['flash'] = function ($container) {
        $session = $container['session'];

        return new Kernel\Flash($session);
    };

    $container['errorHandler'] = function ($container) {
        $settings = $container->get('settings');

        return new Dopesong\Slim\Error\Whoops($settings['displayErrorDetails']);
    };

    $container['phpErrorHandler'] = function ($container) {
        return $container['errorHandler'];
    };

    $container['lazy'] = function ($container) {
        return new Kernel\Lazy\Load();
    };

    Tracy\Debugger::enable();
    $app->add(new RunTracy\Middlewares\TracyMiddleware($app));
