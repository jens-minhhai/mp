<?php

namespace Service\Attribute\Dictionary\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Kernel\Traits\Repository\Association\ReadTrait;

    protected $pk = 'id';
    protected $table = 'attribute_dictionary';

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
