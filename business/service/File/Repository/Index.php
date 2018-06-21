<?php
namespace Service\File\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    protected $pk = 'id';
    protected $table = 'file';

    public function target(int $id, array $field = [])
    {
        return $this->field($field)
                    ->where($this->pk, $id)
                    ->alive()
                    ->first();
    }
}
