<?php

namespace Service\Attribute\Dictionary\Repository;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    protected $pk = 'id';
    protected $table = 'attribute_dictionary';
    protected $fillable = [
        'name',
        'value',
        'target_model',
        'target_id'
    ];

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
