<?php

namespace Kernel\Traits\Domain\Relation;

trait ReadMultipleTrait
{
    use RelationTrait;

    public function getWithRelation()
    {
        $list = $this->get();

        if ($list) {
            return $this->attachRelation($list);
        }

        return $list;
    }

    protected function attachRelation(array $master = [])
    {
        foreach ($this->relation as $name => $target) {
            switch ($target['type']) {
                case DOMAIN_ASSOCIATION_PRIMARY:
                    $master = $this->attachPrimaryRelation($name, $target, $master);
                    break;
                case DOMAIN_ASSOCIATION_FOREIGN:
                    $master = $this->attachForeignRelation($name, $target, $master);
                    break;
                default:
            }
        }

        return $master;
    }

    protected function attachPrimaryRelation($name, $option, $master = [])
    {
        extract($option);
        
        $key = '{n}.' . $master_field;
        $relation_id_list = array_unique(array_extract($master, $key));
        
        $relation = $trigger->getByIdList($relation_id_list);
        $relation = array_pluck($relation, '{n}.id', '{n}');
        
        foreach ($master as &$target) {
            $key = $target[$master_field];
            $target[$name] = $relation[$key];
        }

        return $master;
    }

    protected function attachForeignRelation($name, $option, $master = [])
    {
        extract($option);

        $target_id_list = array_extract($master, '{n}.id', '{n}.id');

        if (isset($func)) {
            if (isset($args)) {
                if (!empty($dynamic)) {
                    foreach ($args as &$arg) {
                        $arg = $$arg;
                    }
                }
            } else {
                $args = [];
            }

            $master[$name] = call_user_func_array([$trigger, $func], $args);

            return $master;
        }

        $relation = $trigger->getByTargetIdList($target_id_list, $master_model);
        $relation = array_pluck($relation, '{n}.id', '{n}', '{n}.target_id');
        foreach ($master as &$target) {
            $key = $target['id'];
            if (empty($unique)) {
                $target[$name] = $relation[$key];
            } else {
                $target[$name] = array_first($relation[$key] ?? []);
            }
        }

        return $master;
    }
}
