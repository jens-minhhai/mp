<?php

namespace Admin\Traits\Domain\Attribute;

use Factory;

trait DeleteTrait
{
    public function delete(array $id = [], &$error = [])
    {
        return $this->transaction(
            function () use ($id, &$error) {
                return $this->deleteAttr($id, $error);
            },
            function () use ($id) {
                return $this->repository->delete($id);
            }
        );
    }

    public function deleteAttr(array $master_id = [], array &$error = [])
    {
        $list = [
            'attribute.collection.delete',
            'attribute.text.delete',
            'attribute.dictionary.delete',
        ];
        foreach ($list as $instance) {
            $trigger = Factory::service($instance);

            $flag = $trigger->deleteByTargetIdList($master_id, $this->attribute_model, $error);
            if (!$flag) {
                return false;
            }
        }

        return true;
    }
}
