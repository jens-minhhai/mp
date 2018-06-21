<?php

namespace Service\Auth\Login\Repository;

use Kernel\Base\Repository\Read;

class Local extends Read
{
    protected $pk = 'id';
    protected $table = 'auth';

    public function getByAccount(string $account, array $group_id_list = [], array $field = [])
    {
        return $this->field($field)
                    ->where('account', $account)
                    ->where('provider', AUTH_PROVIDER_LOCAL)
                    ->where('mode', ENABLE)
                    ->in('group_id', $group_id_list)
                    ->first();
    }
    
    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
