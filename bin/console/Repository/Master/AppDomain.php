<?php

namespace Console\Repository\Master;

class AppDomain extends Base
{
    protected $table = 'app_domain';
    protected $fillable = [
        'domain',
        'app_id',
        'creator'
    ];
}
