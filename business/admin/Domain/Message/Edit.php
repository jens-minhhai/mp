<?php

namespace Admin\Domain\Message;

use Admin\Repository\Message\Index as Repository;
use Kernel\Base\Domain\Read;

class Edit extends Read
{
    protected $attribute = [
        'id',
        'code',
        'value',
        'mode'
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }
}
