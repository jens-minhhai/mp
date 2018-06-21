<?php

namespace App\Middleware;

use Kernel\Factory;
use Request;

class Business
{
    public function __invoke($request, $response, $next)
    {
        $business = $this->boot();

        if (array_key_exists('redirect', $business)) {
            return $response->withRedirect($business['redirect']);
        }

        $request = $request->withAttribute('business', $business);

        return $next($request, $response);
    }

    protected function boot()
    {
        extract($this->getTriggerName());

        return $this->execute($class, $function);
    }

    protected function execute(string $class, string $function)
    {
        $request = Request::all();

        $trigger = Factory::singleton(namespace_case($class));

        $args = [];
        foreach ($request['name'] as $key => $arg) {
            if (substr($key, -2) == 'id') {
                $arg = (int) $arg;
            }
            array_push($args, $arg);
        }

        call_user_func_array([$trigger, $function], $args);

        return $trigger->result();
    }

    protected function getTriggerName()
    {
        $request = Request::all();
        $args = $request['path'];

        $controller = array_shift($args);
        $action = array_shift($args);

        $function = $action;
        if ($request['method'] != 'get') {
            $function = $request['method'] . ucfirst($action);
        }

        // $request['channel_code'] = '';
        $class = self::getClassName($controller, $request['channel_code']);

        return compact('class', 'function', 'controller', 'action');
    }

    protected function getClassName(string $controller, string $channel_code)
    {
        $channel_code = $channel_code ?: 'arena';

        return $channel_code . '.controller.' . $controller;
    }
}
