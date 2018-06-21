<?php

namespace Service\Mail\Detail\Domain;

use Service\Mail\Detail\Repository\Delay as Repository;
use Kernel\Base\Domain\Write;

class Delay extends Write
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

    public function queue(array $data, int $delay)
    {
        $data['_from'] = json_encode($data['from']);
        $data['_to'] = json_encode($data['to']);

        if (isset($data['cc'])) {
            $data['_cc'] = json_encode($data['cc']);
        }
        if (isset($data['bcc'])) {
            $data['_bcc'] = json_encode($data['bcc']);
        }

        $data['delivery_time'] = function () use ($delay) {
            return "NOW() + INTERVAL {$delay} MINUTE";
        };
        $data = array_mask($data, $this->attribute);

        return $this->repository->write($data);
    }
}
