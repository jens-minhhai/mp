<?php

namespace Service\Attribute\Text\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Kernel\Traits\Repository\Association\ReadTrait;

    protected $pk = 'id';
    protected $table = 'attribute_text';

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
