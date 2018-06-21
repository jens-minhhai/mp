<?php

namespace Service\Menu\Domain;

use Service\Menu\Repository\Index as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'id',
        'title',
        'url',
        'target',
        'caption',
        'mode',
        'parent_id',
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function root(string $root)
    {
        $instance = Factory::global_service('code.index');
        $tree = $instance->code($root, Config::read('app.module.menu'));
        
        return $tree['target_id'] ?? '';
    }

    public function getTree(string $root = '', bool $all = false)
    {
        $root = $this->root($root);
        if (!$root) {
            return [];
        }
        
        $result = $this->repository->tree($root, $this->attribute);
        
        if (!$all) {
            foreach ($result as $key => $node) {
                if ($node['id'] == $root) {
                    unset($result[$key]);
                    break;
                }
            }
        }
        
        return array_tree($result, $root);
    }
}
