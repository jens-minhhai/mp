<?php

namespace Console\Repository\Master;

use Kernel\Lib\Security;

class Auth extends Base
{
    protected $table = 'auth';
    protected $fillable = [
        'account',
        'password',
        'provider',
        'group_id',
        'account_id',
        'app_id',
        'creator'
    ];
}
