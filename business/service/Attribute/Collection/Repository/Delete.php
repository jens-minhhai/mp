<?php

namespace Service\Attribute\Collection\Repository;

use Kernel\Base\Repository\Del;

class Delete extends Del
{
    use \Kernel\Traits\Repository\Association\DeleteTrait;

    protected $table = 'attribute_collection';

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
