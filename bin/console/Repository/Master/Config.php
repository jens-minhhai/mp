<?php

namespace Console\Repository\Master;

class Config extends Base
{
    protected $table = 'config';
    protected $fillable = [
        'code',
        'value',
        'mode',
        'channel_id',
        'creator',
        'app_id'
    ];
}
