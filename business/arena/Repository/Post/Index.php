<?php

namespace Frontend\Repository\Post;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    protected $name = 'post';
    protected $table = 'post';
    protected $fillable = [];
}
