<?php

namespace Admin\Service\Account\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    protected $table = 'account';

    public function target(int $id, array $field)
    {
        return $this->field($field)
                    ->where('id', $id)
                    ->first();
    }

    protected function db()
    {
        return db()->shift()
                   ->from($this->table);
    }
}
