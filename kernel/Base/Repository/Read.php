<?php

namespace Kernel\Base\Repository;

class Read extends Base
{
    public function get(array $field)
    {
        return $this->field($field)
                    ->order('id', 'desc')
                    ->get();
    }

    public function target(int $id, array $field)
    {
        return $this->field($field)
                    ->where('id', $id)
                    ->first();
    }

    public function getByIdList(array $id_list, array $field)
    {
        return $this->field($field)
                    ->in($this->pk, $id_list)
                    ->unlimit()
                    ->get();
    }
}
