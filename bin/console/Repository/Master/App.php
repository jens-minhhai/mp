<?php

namespace Console\Repository\Master;

class App extends Base
{
    protected $table = 'app';
    protected $fillable = [
        'id',
        'title',
        'token',
        'creator'
    ];
}
