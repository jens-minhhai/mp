<?php

namespace Console\Repository\Master;

class Group extends Base
{
    protected $table = 'group';
    protected $fillable = [
        'id',
        'title',
        'channel_id',
        'locale_id',
        'app_id',
        'creator'
    ];
}
