<?php

namespace Service\File\Repository;

use Kernel\Base\Repository\Write;

class Upload extends Write
{
    protected $pk = 'id';
    protected $table = 'file';
    protected $fillable = [
        'real_name',
        'directory',
        'name',
        'size',
        'mime',
        'extension'
    ];

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
