<?php

namespace Admin\Repository\Post;

use Factory;
use Kernel\Base\Repository\Read;

class Index extends Read
{
    protected $table = 'post';
    protected $fillable = [];

    public function scope()
    {
        $scope = parent::scope();

        $tree = Factory::service('category.collection')->tree('post');
    
        if ($tree) {
            $scope['category_id'] = ['in', 'category_id', array_extract($tree, '{n}.id')];
        } else {
            $scope['category_id'] = ['in', 'category_id', [0]];
        }

        return $scope;
    }
}
