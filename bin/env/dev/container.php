<?php

    $container = $app->getContainer();

    $container['db'] = function ($container) {
        return new Kernel\Db\MySql();
    };

    $container['log'] = function ($container) {
        $config = $container['settings']['log']['stream'];

        return new Kernel\Log\Stream($config);
    };

    $container['errorHandler'] = function ($container) {
        $settings = $container->get('settings');

        return new Dopesong\Slim\Error\Whoops($settings['displayErrorDetails']);
    };

    $container['phpErrorHandler'] = function ($container) {
        return $container['errorHandler'];
    };
