<?php

namespace Service\Mail\Template\Domain;

use Service\Mail\Template\Repository\Index as Repository;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        '_from',
        '_to',
        '_cc',
        '_bcc',
        'title',
        'content',
        'artisan',
        'driver',
        'delay',
        'attemp'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getByName(string $name)
    {
        $template = $this->repository->getByName($name, $this->attribute);

        return $this->format($template);
    }

    private function format(array $data)
    {
        $field = [
            'from',
            'to',
            'cc',
            'bcc',
        ];
        foreach ($field as $f) {
            if (!empty($data['_' . $f])) {
                $data[$f] = json_decode($data['_' . $f], true);
                unset($data['_' . $f]);
            }
        }

        if (!empty($data['content'])) {
            $data['content'] = nl2br($data['content']);
        }

        return $data;
    }
}
