<?php

namespace Service\Attribute\Dictionary\Repository;

use Kernel\Base\Repository\Del;

class Delete extends Del
{
    use \Kernel\Traits\Repository\Association\DeleteTrait;

    protected $table = 'attribute_dictionary';

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
