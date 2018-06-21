<?php

namespace Kernel\Traits\Domain\Relation;

use Factory;

trait ReadSingleTrait
{
    use RelationTrait;

    public function targetWithRelation(int $id = 0)
    {
        $target = $this->target($id);

        if ($target) {
            return $this->attachRelation($target);
        }

        return $target;
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
        $target = $master[$master_field] ?? 0;

        if ($target) {
            $master[$name] = $trigger->target($target);
        }

        return $master;
    }

    protected function attachForeignRelation($name, $option, $master = [])
    {
        extract($option);

        $target_id = $master['id'];

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

            $relation = [
                $target_id => call_user_func_array([$trigger, $func], $args)
            ];
        } else {
            $relation = $trigger->getByTargetId($target_id, $master_model);
            $relation = array_pluck($relation, '{n}.id', '{n}', '{n}.target_id');
        }

        if (empty($unique)) {
            $master[$name] = $relation[$target_id];
        } else {
            $master[$name] = array_first($relation[$target_id] ?? []);
        }

        return $master;
    }
}
