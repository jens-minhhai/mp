<?php

namespace Service\Attribute\Text\Repository;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    protected $pk = 'id';
    protected $table = 'attribute_text';
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
