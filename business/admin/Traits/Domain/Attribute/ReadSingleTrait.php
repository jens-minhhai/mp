<?php

namespace Admin\Traits\Domain\Attribute;

use Factory;

trait ReadSingleTrait
{
    public function target(int $id)
    {
        $target = parent::target($id, $this->attribute);

        if ($this->attribute_enable) {
            $attr = $this->getAttr($id);
            $target = array_merge($target, $attr);
        }

        return $target;
    }

    public function getAttr(int $master_id)
    {
        $attr = [];
        foreach ($this->attribute_virtual as $type => $field) {
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

            $attr = array_merge($attr, $this->$f($master_id, $field));
        }

        return $attr;
    }

    public function getCollection(int $master_id, array $field)
    {
        $trigger = Factory::service('attribute.collection.index');

        $attr = $trigger->getByTargetId($master_id, $this->attribute_model);
        if ($attr) {
            $attr = array_first($attr);

            return $attr['property'];
        }

        return [];
    }

    public function getDictionary(int $master_id, array $field)
    {
        $trigger = Factory::service('attribute.dictionary.index');

        $attr = $trigger->getByTargetId($master_id, $this->attribute_model);
        $attr = array_pluck($attr, '{n}.name', '{n}.value');
        return array_mask($attr, $field);
    }

    public function getText(int $master_id, array $field)
    {
        $trigger = Factory::service('attribute.text.index');

        $attr = $trigger->getByTargetId($master_id, $this->attribute_model);
        $attr = array_pluck($attr, '{n}.name', '{n}.value');
        return array_mask($attr, $field);
    }
}
