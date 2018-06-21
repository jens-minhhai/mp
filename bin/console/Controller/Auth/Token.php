<?php

namespace Console\Controller\Auth;

use Factory;
use Kernel\Lib\Security;

class Token
{
    public function get(string $domain)
    {
        $token = Factory::load('domain.app.index')->getTokenByDomain($domain);
        return Security::sha1($token, $this->getUniqueKey());
    }

    private function getUniqueKey()
    {
        $key = date('YMdH');
        $minute = date('i');
        if ($minute == 59) {
            date('YMdH', time() + 3600);
        }
        $minute = ($minute % 15) ? $minute : $minute + 1;
        $minute = ceil($minute / 15) * 15;
        return $key . ':' . $minute;
    }
}
