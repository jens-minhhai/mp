<?php

namespace Admin\Service\Account\Repository;

use Kernel\Base\Repository\Del;

class Delete extends Del
{
    protected $table = 'account';

    protected function db()
    {
        return db()->shift()
                   ->from($this->table);
    }
}
