<?php

namespace Admin\Service\Account\Repository;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    protected $table = 'account';
    protected $fillable = [
        'fullname',
        'email',
    ];

    protected function db()
    {
        return db()->shift()
                   ->from($this->table);
    }
}
