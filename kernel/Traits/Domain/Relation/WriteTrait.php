<?php

namespace Kernel\Traits\Domain\Relation;

use Factory;

trait WriteTrait
{
    use RelationTrait;

    public function saveRelation(
        array $data = [],
        int $master_id = 0,
        array $master = [],
        array &$error = []
    ) {
        foreach ($this->relation as $key => $option) {
            if (empty($data[$key])) {
                if (!empty($option['force'])) {
                    $data[$key] = [];
                } else {
                    continue;
                }
            }

            $err = [];
            $func = 'save' . ucfirst($key);

            $flag = $this->$func(
                        $data[$key],
                        $master_id,
                        $option['master_model'],
                        $master,
                        $option,
                        $err
                    );
            if (!$flag) {
                $error[$key] = $err;

                return false;
            }
        }

        return true;
    }

    public function updateRelation(
        array $update = [],
        array $master_id_list = [],
        array $master = [],
        array &$error = []
    ) {
        foreach ($this->relation as $key => $option) {
            $err = [];
            $func = 'update' . ucfirst($key);

            $flag = $this->$func(
                        $update[$key],
                        $master_id_list,
                        $option['master_model'],
                        $master,
                        $option,
                        $err
                    );

            if (!$flag) {
                $error[$key] = $err;

                return false;
            }
        }

        return true;
    }

    public function editWithRelation(array $master = [], int $master_id = 0, array $relation_data = [], array &$error = [])
    {
        return $this->transaction(
            function () use ($master, $master_id, &$error) {
                $this->edit($master, $master_id, $error);

                return true;
            },
            function () use ($relation_data, $master, $master_id, &$error) {
                return $this->saveRelation($relation_data, $master_id, $master, $error);
            }
        );
    }

    public function addWithRelation(array $master = [], int &$master_id = 0, array $relation_data = [], array &$error = [])
    {
        return $this->transaction(
            function () use ($master, &$master_id, &$error) {
                $this->add($master, $master_id, $error);

                return true;
            },
            function () use ($relation_data, $master, &$master_id, &$error) {
                return $this->saveRelation($relation_data, $master_id, $master, $error);
            }
        );
    }
}
