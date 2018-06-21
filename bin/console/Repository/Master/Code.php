<?php

namespace Console\Repository\Master;

class Code extends Base
{
    protected $table = 'code';
    protected $fillable = [
        'code',
        'target_model',
        'target_id',
        'channel_id',
        'locale_id',
        'app_id',
        'creator'
    ];
}
