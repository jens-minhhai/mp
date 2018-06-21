<?php

namespace Service\Attribute\Text\Repository;

use Kernel\Base\Repository\Del;

class Delete extends Del
{
    use \Kernel\Traits\Repository\Association\DeleteTrait;

    protected $table = 'attribute_text';

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
