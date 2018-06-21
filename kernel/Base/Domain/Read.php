<?php

namespace Kernel\Base\Domain;

use Factory;

class Read extends Base
{
    public function target(int $id)
    {
        return $this->repository->target($id, $this->attribute);
    }

    public function get()
    {
        return $this->repository->get($this->attribute);
    }

    public function getByIdList(array $id_list)
    {
        return $this->repository->getByIdList($id_list, $this->attribute);
    }
}
