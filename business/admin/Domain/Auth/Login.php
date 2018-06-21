<?php

namespace Admin\Domain\Auth;

use Factory;

class Login
{
    public function login($token, $type = 'local')
    {
        $auth = Factory::global_service('auth.login.' . $type);
        
        return $auth->login($token);
    }

    public function logout()
    {
        $auth = Factory::global_service('auth.logout');

        return $auth->logout();
    }
}
