<?php

namespace Admin\Domain\Config;

use Admin\Repository\Config\Input as Repository;
use Kernel\Base\Domain\Write;

class Input extends Write
{
    protected $attribute = [
        'id',
        'code',
        'value',
        'mode'
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function modify(array $id_list, array $data)
    {
        $data = array_get($data, 'value', []);

        $update = [];
        foreach ($id_list as $id) {
            if (isset($data[$id])) {
                $update[$id] = [
                    'value' => $data[$id]
                ];
            }
        }

        if ($update) {
            return $this->repository->modify($update);
        }

        return true;
    }
}
