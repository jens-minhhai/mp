<?php

namespace Console\Repository\Master;

class Seo extends Base
{
    protected $table = 'seo';
    protected $fillable = [
        'alias',
        'url',
        'canonical',
        'title',
        'keyword',
        'description',
        'priority',
        'mode',
        'target_model',
        'target_id',
        'locale_id',
        'channel_id',
        'creator',
        'app_id'
    ];
}
