<?php

namespace Admin\Traits\Repository;

trait TreeTrait
{
    use \Kernel\Traits\Repository\TreeTrait;
    
    public function nest(array $data = [], string $display = 'title', string $spacer = '&nbsp;&nbsp;&nbsp;')
    {
        $id_list = array_extract($data, '{n}.id');
        $position_list = $this->select('id', $this->left, $this->right)
                         ->in('id', $id_list)
                         ->order($this->left)
                         ->unlimit()
                         ->get();

        $position_list = array_pluck($position_list, '{n}.id', '{n}');
        if ($position_list) {
            $callable = function ($target) use ($position_list) {
                $id = $target['id'];
                $position = $position_list[$id] ?? [];

                return array_merge($target, $position);
            };
            $data = array_map($callable, $data);
            
            return $this->makeNest($data);
        }

        return $data;
    }

    protected function makeNest(array $data = [], string $display = 'title', string $spacer = '&nbsp;&nbsp;&nbsp;')
    {
        $right = [];
        $result = [];
        
        foreach ($data as $key => $row) {
            if (count($right) > 0) {
                // check if we should remove a node from the stack

                $countRight = count($right) - 1;
                while (isset($right[$countRight]) && $right[$countRight] < $row[$this->right]) {
                    array_pop($right);
                    $countRight = count($right) - 1;
                }
            }

            // display indented node title
            $modified = str_repeat($spacer, count($right)) . $row[$display];
            $key = $row[$this->pk] ?? $key;
            $result[$key] = $modified;
            $right[] = $row[$this->right];
        }
        return $result;
    }
}
