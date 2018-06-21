<?php

namespace Kernel\Traits\Repository\Association;

trait DeleteTrait
{
    use \Kernel\Traits\Repository\MasterTrait;

    public function deletetByTargetId(int $target_id, int $target_model)
    {
        return $this->where('target_model', $target_model)
                    ->where('target_id', $target_id)
                    ->delete($this->track());
    }

    public function deleteByTargetIdList(array $target_id, int $target_model)
    {
        return $this->where('target_model', $target_model)
                     ->in('target_id', $target_id)
                     ->delete($this->track());
    }
}
