<?php

namespace Kernel\Traits\Domain\Attribute;

use Factory;

trait ReadMultipleTrait
{
    public function get()
    {
        $target_list = parent::get();

        if ($this->attribute_enable) {
            $master_id_list = array_extract($target_list, '{n}.id');

            $attr = $this->getAttr($master_id_list);
            return $this->mapAttr($target_list, $attr);
        }

        return $target_list;
    }

    public function getAttr(array $master_id_list)
    {
        $result = [];
        foreach ($this->attribute_virtual as $type => $field) {
            if (!$field) {
                continue;
            }
            switch ($type) {
                case ATTRIBUTE_COLLECTION:
                    $f = 'getCollection';
                    break;
                case ATTRIBUTE_TEXT:
                    $f = 'getText';
                    break;
                default:
                    $f = 'getDictionary';
            }

            $attr = $this->$f($master_id_list, $field);
            foreach ($attr as $master_id => $collection) {
                if (isset($result[$master_id])) {
                    $result[$master_id] = array_merge($result[$master_id], $collection);
                } else {
                    $result[$master_id] = $collection;
                }
            }
        }

        return $result;
    }

    protected function getCollection(array $master_id_list, array $field)
    {
        $trigger = Factory::global_service('attribute.collection.index');

        $attr = $trigger->getByTargetIdList($master_id_list, $this->attribute_model);

        $result = [];
        foreach ($attr as $item) {
            $master_id = $item['target_id'];
            $result[$master_id] = $result[$master_id] ?? [];

            $collection = array_mask($item['property'], $field);
            $result[$master_id] = array_merge($result[$master_id], $collection);
        }

        return $result;
    }

    protected function getDictionary(array $master_id_list, array $field)
    {
        $trigger = Factory::global_service('attribute.dictionary.index');
        $attr = $trigger->getByTargetIdList($master_id_list, $this->attribute_model);

        return $this->filterAttr($attr, $field);
    }

    protected function getText(array $master_id_list, array $field)
    {
        $trigger = Factory::global_service('attribute.text.index');
        $attr = $trigger->getByTargetIdList($master_id_list, $this->attribute_model);
        return $this->filterAttr($attr, $field);
    }

    protected function filterAttr(array $attr, array $field)
    {
        $result = [];
        foreach ($attr as $item) {
            extract($item);
            if (in_array($name, $field)) {
                $result[$target_id] = $result[$target_id] ?? [];
                $result[$target_id][$name] = $value;
            }
        }
        return $result;
    }

    protected function mapAttr(array $target_list, array $attr)
    {
        foreach ($target_list as &$target) {
            $master_id = $target['id'];
            if (isset($attr[$master_id])) {
                $target = array_merge($target, $attr[$master_id]);
            }
        }

        return $target_list;
    }
}
