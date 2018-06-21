<?php

namespace Kernel\Base\Domain;

class Write extends Base
{
    public function updateByIdList(array $data, array $target_id)
    {
        $data = array_mask($data, $this->attribute);

        return $this->repository->updateByIdList($data, $target_id);
    }

    public function save(array $data, int &$id = 0)
    {
        $data = array_mask($data, $this->attribute);

        return $this->repository->save($data, $id);
    }

    public function add(array $data, int &$last_inserted_id = 0)
    {
        $data = array_mask($data, $this->attribute);
        return $this->repository->add($data, $last_inserted_id);
    }

    public function edit(array $data, int $target_id)
    {
        $data = array_mask($data, $this->attribute);

        return $this->repository->edit($data, $target_id);
    }

    public function init(array $default)
    {
        $data = array_fill_keys($this->attribute, '');
        if ($default) {
            return array_merge($data, $default);
        }

        return $data;
    }
}
