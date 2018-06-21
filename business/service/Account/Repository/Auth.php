<?php

namespace Service\Account\Repository;

use Kernel\Base\Repository\Read;

class Auth extends Read
{
    protected $pk = 'id';
    protected $table = 'account';

    public function target(int $id, array $field = [])
    {
        return $this->field($field)
                    ->where($this->pk, $id)
                    ->where('mode', ENABLE)
                    ->first();
    }
    
    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
