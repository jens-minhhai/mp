<?php

namespace Admin\Service\Category;

use Admin\Service\Category\Domain\Tree as Domain;

class Tree
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function nest(string $tree = '', bool $all = true)
    {
        return $this->domain->nest($tree, $all);
    }
}
