<?php

namespace Admin\Repository\Auth\Admin;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    protected $table = 'auth';
    protected $fillable = [
        'account',
        'password',
        'mode',
        'provider',
        'group_id',
        'account_id'
    ];

    public function updateMode(array $id_list, int $mode)
    {
        $data = [
            'mode' => $mode
        ];

        return $this->updateByIdList($data, $id_list);
    }
}
