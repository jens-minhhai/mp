<?php

namespace Admin\Service\Account\Repository;

use Kernel\Base\Repository\Read;

class Validation extends Read
{
    protected $table = 'account';

    public function getByEmail(string $email, int $account_id, array $field)
    {
        return $this->field($field)
                    ->not('id', $account_id)
                    ->where('email', $email)
                    ->first();
    }

    protected function db()
    {
        return db()->shift()
                   ->from($this->table);
    }
}
