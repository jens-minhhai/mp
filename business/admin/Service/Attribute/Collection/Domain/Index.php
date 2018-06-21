<?php

namespace Admin\Service\Attribute\Collection\Domain;

use Admin\Service\Attribute\Collection\Repository\Index as Repository;
use Kernel\Base\Domain\Read;

class Index extends Read
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

    public function getByTargetId(int $target_id, int $target_model)
    {
        $collection = $this->repository->getByTargetId($target_id, $target_model, $this->attribute);

        return $this->extractAttr($collection);
    }

    public function getByTargetIdList(array $target_id_list, int $target_model)
    {
        $collection = $this->repository->getByTargetIdList($target_id_list, $target_model, $this->attribute);

        return $this->extractAttr($collection);
    }

    private function extractAttr(array $collection)
    {
        return array_map(function ($item) {
            $item['property'] = json_decode($item['property'], true);
            return $item;
        }, $collection);
    }
}
