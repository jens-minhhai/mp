<?php

namespace Service\Attribute\Collection\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Kernel\Traits\Repository\Association\ReadTrait;

    protected $pk = 'id';
    protected $table = 'attribute_collection';

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
