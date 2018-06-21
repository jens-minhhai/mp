<?php

namespace Admin\Service\Auth\Repository;

use Kernel\Base\Repository\Read;

class Validation extends Read
{
    protected $table = 'auth';

    public function getByAccount(string $account, int $auth_id, array $field)
    {
        return $this->field($field)
                    ->not('id', $auth_id)
                    ->where('account', $account)
                    ->first();
    }

    protected function db()
    {
        return db()->shift()
                   ->from($this->table);
    }
}
