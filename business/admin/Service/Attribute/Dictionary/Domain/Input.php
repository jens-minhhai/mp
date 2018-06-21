<?php

namespace Admin\Service\Attribute\Dictionary\Domain;

use Admin\Service\Attribute\Dictionary\Repository\Input as Repository;
use Kernel\Base\Domain\Write;
use Factory;

class Input extends Write
{
    protected $attribute = [
        'id',
        'name',
        'value',
        'target_id',
        'target_model'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function saveAttr(array $data = [], int $target_id, int $target_model, array &$error = [])
    {
        $trigger = Factory::global_service('attribute.dictionary.domain.index');
        $mapping = $trigger->getByTargetId($target_id, $target_model);
        $mapping = array_pluck($mapping, '{n}.name', '{n}.id');

        foreach ($data as $name => $value) {
            if (isset($mapping[$name])) {
                $save = compact('value');
                $this->repository->edit($save, $mapping[$name]);
            } else {
                $save = compact('name', 'value', 'target_id', 'target_model');
                $this->repository->add($save);
            }
        }

        return true;
    }
}
