<?php

namespace Admin\Service\Category;

use Admin\Service\Category\Domain\Collection as Domain;

class Collection
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function tree(string $tree = '', bool $all = true)
    {
        return $this->domain->tree($tree, $all);
    }

    public function getByIdList(array $id_list = [])
    {
        return $this->domain->getByIdList($id_list);
    }
}
