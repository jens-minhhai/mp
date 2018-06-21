<?php

namespace Kernel\Lazy;

use Config;
use Factory;

class Load
{
    public function assign(array $collection, array $criteria, array $name = [])
    {
        $app = container('app');
        $lazy = array_get($app, 'lazy', []);

        $target = [];
        $target_name = [];
        foreach ($criteria as $field => $table) {
            $list = array_pluck($collection, '{n}.' . $field, '{n}.' . $field);
            if ($list) {
                $old = array_get($lazy, 'target.' . $table, []);
                $target[$table] = $old + $list;

                $target_name[$name[$field]] = [
                    $field => $list
                ];
            }
        }

        if ($name) {
            $old = array_get($lazy, 'name', []);
            $name = $old + $target_name;
        }
        $app['lazy'] = compact('target', 'name');

        container('app', $app);
    }

    public function load($data = [])
    {
        $obj_map = Config::read('lazy');
        $lazy = array_get(container('app'), 'lazy', []);

        if ($lazy) {
            $option = [];
            foreach ($lazy['target'] as $name => $target) {
                $instance = $obj_map[$name] ?? '';
                $result = Factory::instance($instance)->getByIdList($target);
                $option[$name] = array_pluck($result, '{n}.id', '{n}');
            }

            return $data + $option;
        }

        return $data;
    }
}
