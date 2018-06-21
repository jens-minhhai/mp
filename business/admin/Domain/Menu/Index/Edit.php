<?php

namespace Admin\Domain\Menu\Index;

use Kernel\Base\Domain\Read;
use Admin\Repository\Menu\Index\Index as Repository;

class Edit extends Read
{
    use \Kernel\Traits\Domain\Relation\ReadSingleTrait;

    protected $attribute = [
        'id',
        'title',
        'url',
        'target',
        'caption',
        'mode',
        'parent_id',
        'priority'
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }
}
