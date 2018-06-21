<?php

namespace Kernel\Traits\Domain\Relation;

trait RelationTrait
{
    protected $relation = [];

    public function modifyRelation(array $option = [], bool $overwrite = false)
    {
        if ($overwrite) {
            $this->relation = $option;
        } else {
            $this->relation = array_merge($this->relation, $option);
        }

        return $this;
    }

    public function withRelation()
    {
    }

    public function getRelation()
    {
        return $this->relation;
    }
}
