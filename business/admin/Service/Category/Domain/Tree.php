<?php

namespace Admin\Service\Category\Domain;

class Tree extends Collection
{
    public function nest(string $tree, bool $all = true)
    {
        $tree = parent::tree($tree, $all);
        
        if ($tree) {
            return $this->repository->nest($tree);
        }
        
        return [];
    }
}
