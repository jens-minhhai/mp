<?php

namespace Service\Category\Domain;

use Service\Category\Repository\Collection as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Read;

class Collection extends Read
{
    protected $attribute = [
        'id',
        'title',
        'priority'
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function root(string $tree)
    {
        $instance = Factory::global_service('code.index');
        $tree = $instance->code($tree, Config::read('app.module.category'));

        return empty($tree['target_id']) ? 0 : $tree['target_id'];
    }

    public function tree(string $tree, bool $all = true)
    {
        $root = $this->root($tree);

        $result = $this->repository->tree($root, $this->attribute);

        if ($result && !$all) {
            foreach ($result as $key => $node) {
                if ($node['id'] == $root) {
                    unset($result[$key]);
                    break;
                }
            }
        }
        
        return $result;
    }

    public function getByIdList(array $id_list)
    {
        return $this->repository->getByIdList($id_list, $this->attribute);
    }
}
