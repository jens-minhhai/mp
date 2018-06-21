<?php

namespace Admin\Controller\Auth;

use Csrf;

use Admin\Controller\Base;

class Token extends Base
{
    public function get()
    {
        $token = [
            'token' => Csrf::generate()
        ];

        return $this->json($token);
    }
}
