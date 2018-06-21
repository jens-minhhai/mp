<?php

namespace Console\Repository\Master;

class Message extends Base
{
    protected $table = 'message';
    protected $fillable = [
        'code',
        'value',
        'mode',
        'channel_id',
        'locale_id',
        'creator',
        'app_id'
    ];
}
