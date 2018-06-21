<?php

namespace Admin\Repository\Auth\Admin;

use Factory;
use Kernel\Base\Repository\Read;

class Index extends Read
{
    protected $table = 'auth';

    public function get(array $field)
    {
        return $this->field($field)
                    ->order('priority')
                    ->get();
    }

    public function scope()
    {
        $group_id = Factory::service('group.index')->get();
        $group_id = array_extract($group_id, '{n}.id');

        $scope = parent::scope();
        $scope['group_id'] = ['in', 'group_id', $group_id];

        return $scope;
    }
}
