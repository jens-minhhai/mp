<?php

namespace Service\File\Domain;

use Config;
use Kernel\Base\Domain\Read;
use Service\File\Repository\Index as Repository;

class Link extends Read
{
    protected $attribute = [
        'id',
        'directory',
        'name',
        'title',
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function target($id, $thumbnail = true)
    {
        $target = $this->repository->target($id, $this->attribute);
        $link = $this->generateLink($target);
        unset($target['directory']);
        unset($target['name']);

        return array_merge($target, $link);
    }

    private function generateLink($target)
    {
        $position = strrpos($target['name'], '.');
        $extension = substr($target['name'], $position);
        $filename = substr($target['name'], 0, $position);

        $base_url = Config::read('app.host.media') . '/' . $target['directory'] . '/';

        return [
            'url' => $base_url . $target['name'],
            'thumbnail_large' => "{$base_url}{$filename}-large{$extension}",
            'thumbnail_medium' => "{$base_url}{$filename}-medium{$extension}",
            'thumbnail_small' => "{$base_url}{$filename}-small{$extension}",
        ];
    }
}
