<?php

namespace Kernel\Base\Repository;

class Del extends Base
{
    public function delete(array $id_list = [])
    {
        return $this->in('id', $id_list)
                    ->delete($this->track());
    }
}
