<?php

namespace Kernel\Traits\Domain\Relation;

use Factory;

trait DeleteTrait
{
    use RelationTrait;

    public function deleteWithRelation(array $master_id, array &$error = [])
    {
        $flag = $this->deleteRelation($master_id, $error);
        if ($flag) {
            return $this->delete($master_id, $error);
        }

        return false;
    }

    protected function deleteRelation(array $id_list, array &$error = [])
    {
        $result = true;
        foreach ($this->relation as $name => $target) {
            $result = $this->deleteForeignRelation($id_list, $target);
            if (!$result) {
                return false;
            }
        }

        return $result;
    }

    protected function deleteForeignRelation(array $id_list, array $option = [])
    {
        extract($option);
        return $trigger->deleteByTargetId($id_list, $master_model);
    }
}
