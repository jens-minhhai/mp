<?php

namespace Console\Repository\Master;

class Account extends Base
{
    protected $table = 'account';
    protected $fillable = [
        'id',
        'email',
        'fullname',
        'app_id',
        'creator'
    ];
}
