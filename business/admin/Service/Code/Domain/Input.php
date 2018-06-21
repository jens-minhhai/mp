<?php

namespace Admin\Service\Code\Domain;

use Admin\Service\Code\Repository\Input as Repository;
use Kernel\Base\Domain\Write;
use Factory;

class Input extends Write
{
    protected $attribute = [
        'id',
        'code',
        'mode',
        'target_id',
        'target_model'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function updateByTargetIdList(array $data, array $target_id_list, int $target_model, array &$error = [])
    {
        $data = $this->format($data);
        $data = array_mask($data, $this->attribute);

        return $this->repository->updateByTargetIdList($data, $target_id_list, $target_model);
    }

    public function save(array $data = [], int &$id = 0, array &$error = [])
    {
        $data = $this->format($data);

        $data = array_mask($data, $this->attribute);

        $domain = Factory::service('code.domain.index');

        $current = $domain->getByTargetId($data['target_id'], $data['target_model']);
        if ($current) {
            $current = array_first($current);
            $data = array_merge($current, $data);
        }

        $id = $data['id'] ?? 0;

        return $this->repository->save($data, $id);
    }

    private function format(array $data)
    {
        if (isset($data['code'])) {
            $data['code'] = trim(substr(str_slug($data['code']), 0, 16), '-');
        }
        return $data;
    }
}
