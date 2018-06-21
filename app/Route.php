<?php

namespace App;

class Route
{
    public function frontend($app)
    {
        $middleware = [
            'App\Middleware\Render',
            'App\Middleware\Business',
            'App\Middleware\Crsf',
            'App\Middleware\Preload',
            'App\Middleware\Uri',
            'App\Middleware\Request',
            'App\Middleware\Db',
            'App\Middleware\Init'
        ];
        $this->load($app, $middleware);
    }

    public function backend($app)
    {
        $middleware = [
            'App\Middleware\Render',
            'App\Middleware\BusinessWithAuth',
            'App\Middleware\Crsf',
            'App\Middleware\Preload',
            'App\Middleware\Uri',
            'App\Middleware\Request',
            'App\Middleware\Db',
            'App\Middleware\Init'
        ];
        $backend = env('APP_REGX_CHANNEL_ADMIN');
        $self = $this;

        $backend = env('APP_REGX_CHANNEL_ADMIN');
        $app->group("/{channel:{$backend}}", function () use ($self, $app, $middleware) {
            $self->load($app, $middleware);
        });
    }

    public function load($app, $middleware)
    {
        $segments = [
            '/{locale:' . env('APP_REGX_LOCALE') . '}[/{url:.*}]',
            '[/{url:.*}]'
        ];

        array_map(function ($segment) use ($app, $middleware) {
            $app = $app->any($segment, function () {
            });
            array_map(function ($middleware) use ($app) {
                $app->add(new $middleware());
            }, $middleware);
        }, $segments);
    }
}
