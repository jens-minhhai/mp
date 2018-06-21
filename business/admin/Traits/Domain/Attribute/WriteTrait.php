<?php

namespace Admin\Traits\Domain\Attribute;

use Factory;

trait WriteTrait
{
    public function add(array $data, int &$id = 0, array &$error = [])
    {
        return $this->transaction(
            function () use ($data, &$id) {
                $master = array_mask($data, $this->attribute);
                return $this->repository->add($master, $id);
            },
            function () use ($data, &$id, &$error) {
                if ($this->attribute_enable) {
                    return $this->saveAttr($data, $id, $error);
                }
                return true;
            }
        );
    }

    public function edit(array $data, int $id, array &$error = [])
    {
        return $this->transaction(
            function () use ($data, $id) {
                $master = array_mask($data, $this->attribute);
                return $this->repository->edit($master, $id);
            },
            function () use ($data, &$id, &$error) {
                if ($this->attribute_enable) {
                    return $this->saveAttr($data, $id, $error);
                }
                return true;
            }
        );
    }

    public function saveAttr(array $master, int $master_id, array &$error = [])
    {
        foreach ($this->attribute_virtual as $type => $field) {
            $field = $this->attribute_virtual[$type];
            $data = array_mask($master, $field);

            if (empty($data)) {
                continue;
            }

            $trigger = $this->getTrigger($type);
            $flag = $trigger->save($data, $master_id, $this->attribute_model, $error);
            if (!$flag) {
                return false;
            }
        }

        return true;
    }

    private function getTrigger(string $type)
    {
        switch ($type) {
            case ATTRIBUTE_COLLECTION:
                $trigger = 'attribute.collection.input';
                break;
            case ATTRIBUTE_TEXT:
                $trigger = 'attribute.text.input';
                break;
            default:
                $trigger = 'attribute.dictionary.input';
        }

        return Factory::service($trigger);
    }
}
