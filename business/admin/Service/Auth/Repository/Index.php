<?php

namespace Admin\Service\Auth\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    protected $table = 'auth';

    protected function db()
    {
        return db()->shift()
                   ->from($this->table);
    }
}
