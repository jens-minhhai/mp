<?php

namespace Admin\Domain\Menu\Index;

use Admin\Repository\Menu\Index\Input as Repository;
use Kernel\Base\Domain\Write;

class Input extends Write
{
    use \Kernel\Traits\Domain\Relation\WriteTrait;

    protected $attribute = [
        'id',
        'title',
        'url',
        'target',
        'caption',
        'mode',
        'parent_id',
        'priority',
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }
}
