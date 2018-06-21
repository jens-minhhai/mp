<?php

namespace Admin\Service\Attribute\Collection\Domain;

use Admin\Service\Attribute\Collection\Repository\Input as Repository;
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
        $trigger = Factory::service('attribute.collection.domain.index');
        $mapping = $trigger->getByTargetId($target_id, $target_model);
        $mapping = array_first($mapping);

        if ($mapping) {
            $data = array_merge($mapping['property'], $data);
            $save = [
                'property' => json_encode($data, JSON_UNESCAPED_UNICODE)
            ];
            $this->repository->edit($save, $mapping['id']);
        } elseif ($data) {
            $property = json_encode($data, JSON_UNESCAPED_UNICODE);
            $save = compact('property', 'target_id', 'target_model');
            $this->repository->add($save);
        }

        return true;
    }
}
