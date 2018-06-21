<?php

namespace Kernel\Base\Repository;

class Write extends Base
{
    public function update(array $data)
    {
        $data = array_mask($data, $this->fillable);
        $data = $this->master($data, REPOSITORY_MODE_UPDATE);

        return $this->apply('update', [$data]);
    }

    public function updateByIdList(array $data, array $id_list)
    {
        $data = array_mask($data, $this->fillable);
        $data = $this->master($data, REPOSITORY_MODE_UPDATE);

        return $this->in('id', $id_list)->update($data);
    }

    public function save(array $data, int &$id = 0)
    {
        if ($id) {
            return $this->edit($data, $id);
        }

        return $this->add($data, $id);
    }

    public function add(array $data, int &$last_inserted_id = 0)
    {
        $data = array_mask($data, $this->fillable);
        $data = $this->master($data, REPOSITORY_MODE_CREATE);

        $flag = db()
                ->from($this->table)
                ->insert($data, $this->escape ?? []);

        $last_inserted_id = $this->lastInsertedId();

        return $flag;
    }

    public function edit(array $data, int $target)
    {
        $data = array_mask($data, $this->fillable);
        $data = $this->master($data, REPOSITORY_MODE_UPDATE);

        return $this->where($this->pk, $target)->update($data);
    }

    public function modify(array $data)
    {
        $update = [];
        $id_list = [];
        foreach ($data as $id => $info) {
            $id_list[$id] = $id;
            foreach ($info as $field => $value) {
                if (!isset($update[$field])) {
                    $update[$field] = [];
                }

                $update[$field][$id] = [
                    [
                        ['where' => [$this->pk, $id]],
                    ],
                    $value
                ];
            }
        }

        $this->in($this->pk, $id_list)
             ->modify($update);

        return $this->in($this->pk, $id_list)
                    ->update($this->master([], REPOSITORY_MODE_UPDATE));
    }
}
