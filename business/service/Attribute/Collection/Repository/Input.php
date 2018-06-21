<?php

namespace Service\Attribute\Collection\Repository;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    protected $pk = 'id';
    protected $table = 'attribute_collection';
    protected $fillable = [
        'property',
        'target_model',
        'target_id'
    ];

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
