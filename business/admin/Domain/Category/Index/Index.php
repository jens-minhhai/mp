<?php

namespace Admin\Domain\Category\Index;

use Admin\Repository\Category\Index\Index as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'id',
        'mode',
        'title',
        'priority',
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function root($root)
    {
        $instance = Factory::service('code.index');
        $tree = $instance->code($root, Config::read('app.module.category'));

        return $tree['target_id'] ?? '';
    }

    public function getTree(string $root = '', bool $all = true)
    {
        $root = $this->root($root);
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
