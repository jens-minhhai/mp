<?php

namespace Admin\Domain\Menu\Index;

use Admin\Repository\Menu\Index\Index as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'id',
        'caption',
        'mode',
        'priority',
        'title',
        'target',
        'url',
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function root(string $root)
    {
        $instance = Factory::service('code.index');
        $tree = $instance->code($root, Config::read('app.module.menu'));
        
        return $tree['target_id'] ?? '';
    }

    public function getTree(string $root = '', bool $all = true)
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

        $result = array_pluck($result, '{n}.id', '{n}');
        if ($result) {
            $tree = $this->repository->nest($result);
            foreach ($result as $key => &$node) {
                $node['display'] = $tree[$key];
            }
        }
    
        return $result;
    }
}
