<?php

namespace Kernel\Traits\Repository;

use App;

trait TreeTrait
{
    private $left = 'left';
    private $right = 'right';
    private $parent = 'parent_id';
    private $unique = 'tree_id';

    public function initTree(array $data = [], int &$last_inserted_id = 0)
    {
        $data = $this->master($data, REPOSITORY_MODE_CREATE);

        $data[$this->left] = 1;
        $data[$this->right] = 2;
        $data[$this->parent] = 0;
        $data[$this->unique] = 0;

        $this->insert($data);

        $unique = $this->lastInsertedId();
        $data = [
            $this->unique => $unique
        ];

        $flag = $this->where($this->pk, $unique)
                    ->update($data);
        if ($flag) {
            $last_inserted_id = $unique;

            return true;
        }

        return false;
    }

    public function deleteTree(array $tree_list = [])
    {
        return $this->in($this->unique, $tree_list)
                    ->delete($this->track());
    }

    public function tree(int $id = 0, array $field = [])
    {
        return $this->field($field)
                    ->where($this->unique, $id)
                    ->order($this->left)
                    ->unlimit()
                    ->get();
    }

    public function addNode(array $data = [], int &$last_inserted_id = 0)
    {
        $this->begin();
        $left = $this->left;
        $right = $this->right;
        $unique = $this->unique;

        $node = $data[$this->parent];
        
        $parent = $this->node($node, [$left, $right, $unique]);
        if (!$parent) {
            return false;
        }

        $keypoint = $parent[$right];
        $tree = $parent[$unique];

        $update = [
            $right => function () use ($right) {
                return "`{$right}`+2";
            }
        ];

        $this->where($right, $keypoint, '>=')
             ->where($unique, $tree)
             ->update($update);

        $update = [
            $left => function () use ($left) {
                return "`{$left}`+2";
            }
        ];

        $this->where($left, $keypoint, '>=')
             ->where($unique, $tree)
             ->update($update);

        $data = $this->master($data, REPOSITORY_MODE_CREATE);
        $data[$left] = $keypoint;
        $data[$right] = $keypoint + 1;
        $data[$unique] = $tree;

        if ($this->insert($data)) {
            $last_inserted_id = $this->lastInsertedId();
        }

        if ($this->balance($tree)) {
            $this->commit();

            return true;
        }
        
        $this->rollback();

        return false;
    }

    public function editNode(array $data = [], int $target = 0)
    {
        $this->begin();
        $left = $this->left;
        $right = $this->right;
        $unique = $this->unique;
        $parent = $this->parent;
        $priority = 'priority';

        $node = $target;
        $target = $this->node($node, [$parent, $left, $right, $unique, $priority]);
        if (!$target) {
            return false;
        }

        $data = $this->master($data, REPOSITORY_MODE_UPDATE);

        $this->where($this->pk, $node)
             ->update($data);
        
        if ($target[$parent] == $data[$parent] &&
            isset($data[$priority]) &&
            $target[$priority] == $data[$priority]
        ) {
            $this->commit();

            return true;
        }

        if ($this->balance($target[$unique])) {
            $this->commit();

            return true;
        }

        $this->rollback();

        return false;
    }

    public function deleteNode(int $id)
    {
        $left = $this->left;
        $right = $this->right;
        $unique = $this->unique;
        $parent = $this->parent;

        $target = $this->node($id, [$parent, $left, $right, $unique]);

        if (!$target) {
            return false;
        }

        $this->begin();
        $tree = $target[$unique];

        $count = $target[$parent] ? 1 : 0;
        if ($count) {
            $nodes = $this->select($this->pk)
                          ->where($unique, $tree)
                          ->between($left, $target[$left], $target[$right])
                          ->unlimit()
                          ->get();
            $count = count($nodes);
        }

        $this->between($left, $target[$left], $target[$right])
             ->where($unique, $target[$unique])
             ->delete($this->track());

        if ($count) {
            $delta = $count * 2;

            $update = [
                $left => function () use ($left, $delta) {
                    return "`{$left}`-{$delta}";
                }
            ];

            $this
                ->where($left, $target[$left], '>')
                ->where($unique, $tree)
                ->update($update);

            $update = [
                $right => function () use ($right, $delta) {
                    return "`{$right}`-{$delta}";
                }
            ];

            $this
                ->where($right, $target[$right], '>')
                ->where($unique, $tree)
                ->update($update);
        }

        $this->commit();

        return true;
    }

    public function node(int $id, array $field = [])
    {
        return $this->field($field)
                    ->where($this->pk, $id)
                    ->first();
    }

    public function extract(int $id, array $field = [])
    {
        $left = $this->left;
        $right = $this->right;
        $unique = $this->unique;

        $parent = $this->node($id, [$left, $right, $unique]);

        if ($parent) {
            return $this->children($parent, $field);
        }

        return [];
    }

    protected function children(array $parent = [], array $field = [])
    {
        return $this->field($field)
                  ->where($this->unique, $parent[$this->unique])
                  ->between($this->left, $parent[$this->left], $parent[$this->right])
                  ->order($this->left)
                  ->get();
    }

    /**
     * build mttp tree attribute's (left, right) value
     */
    public function balance(int $tree)
    {
        $root = $this->select($this->pk, $this->left)
                    ->where($this->pk, $tree)
                    ->first();

        if (empty($root)) {
            return false;
        }

        $left = $root[$this->left] ?? 1;

        $affected = [];
        $this->makeBalance($root[$this->pk], $left, $affected);

        $update = [];
        foreach ($affected as $id => $info) {
            foreach ($info as $field => $value) {
                if (!isset($update[$field])) {
                    $update[$field] = [];
                }

                $update[$field][$id] = [
                    [
                        ['where' => [$this->pk, $id]],
                    ],
                    $value
                ];
            }
        }

        $this->in('id', array_keys($affected))
             ->modify($update);

        return true;
    }

    private function makeBalance($node, $left, &$affected = [])
    {
        $right = $left + 1;

        $children = $this
                    ->select($this->pk)
                    ->where($this->parent, $node)
                    ->order('priority')
                    ->get();

        foreach ($children as $child) {
            $right = $this->makeBalance($child[$this->pk], $right, $affected);
        }

        $affected[$node] = [
            $this->left => $left,
            $this->right => $right,
        ];

        return $right + 1;
    }

    /////////////////////////
    public function build($data = [], $root = 0)
    {
        $new = [];
        foreach ($data as $id => $item) {
            $new[$item[$this->parent]][$id] = $item;
        }

        $result = $this->makeBuild($new, array($data[$root]));

        return current($result);
    }

    private function makeBuild(&$list, $parent)
    {
        $tree = [];
        foreach ($parent as $id => $l) {
            if (isset($list[$l[$this->pk]])) {
                $l['children'] = $this->makeBuild($list, $list[$l[$this->pk]]);
            }

            $tree[$id] = $l;
        }

        return $tree;
    }
}
