<?php

namespace Kernel\Traits\Repository\Association;

trait ReadTrait
{
    public function getByTargetId(int $target_id, string $model, array $field = [])
    {
        return $this->field($field)
                    ->where('target_id', $target_id)
                    ->where('target_model', $model)
                    ->get();
    }

    public function getByTargetIdList(array $target_id, string $model, array $field = [])
    {
        return $this->field($field)
                    ->in('target_id', $target_id)
                    ->where('target_model', $model)
                    ->get();
    }
}
