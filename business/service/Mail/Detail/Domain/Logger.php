<?php

namespace Service\Mail\Detail\Domain;

use Service\Mail\Detail\Repository\Logger as Repository;
use Kernel\Base\Domain\Write;

class Logger extends Write
{
    protected $attribute = [
        '_from',
        '_to',
        '_cc',
        '_bcc',
        'title',
        'content',
        'attemp',
        'mode',
        'delivery_time',
        'flush_time',
        'artisan',
        'driver'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function write(array $data)
    {
        $data['_from'] = json_encode($data['from']);
        $data['_to'] = json_encode($data['to']);

        if (isset($data['cc'])) {
            $data['_cc'] = json_encode($data['cc']);
        }
        if (isset($data['bcc'])) {
            $data['_bcc'] = json_encode($data['bcc']);
        }
        $data = array_mask($data, $this->attribute);

        return $this->repository->write($data);
    }
}
