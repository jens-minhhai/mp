<?php

namespace App\Middleware;

use Config;
use Request;
use Kernel\Factory;

class BusinessWithAuth extends Business
{
    protected function boot()
    {
        extract($this->getTriggerName());
        if (auth_logined()) {
            if (auth_allow($controller, $action)) {
                return $this->execute($class, $function);
            }

            abort(401, 'Unauthorized');
        }

        if (auth_fallback($controller, $action)) {
            return $this->execute($class, $function);
        }

        return $this->redirect();
    }

    protected function redirect()
    {
        return [
            'redirect' => base_url() . '/login',
            'code' => 302
        ];
    }
}
