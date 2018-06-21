<?php

namespace Service\Attribute\Collection\Domain;

use Service\Attribute\Collection\Repository\Input as Repository;
use Kernel\Base\Domain\Write;
use Factory;

class Input extends Write
{
    protected $attribute = [
        'id',
        'property',
        'target_id',
        'target_model'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function saveAttr(array $data = [], int $target_id, int $target_model, array &$error = [])
    {
        $trigger = Factory::global_service('attribute.collection.domain.index');
        $mapping = $trigger->getByTargetId($target_id, $target_model);
        $mapping = array_first($mapping);

        if ($mapping) {
            $old = json_decode($mapping['property'], true);
            $data = array_merge($old, $data);
            $property = json_encode($data, JSON_UNESCAPED_UNICODE);
            $save = compact('property');
            $this->repository->edit($save, $mapping['id']);
        } elseif ($data) {
            $property = json_encode($data, JSON_UNESCAPED_UNICODE);
            $save = compact('property', 'target_id', 'target_model');
            $this->repository->add($save);
        }

        return true;
    }
}
