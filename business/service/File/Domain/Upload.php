<?php

namespace Service\File\Domain;

use Service\File\Repository\Upload as Repository;
use Kernel\Base\Domain\Write;

class Upload extends Write
{
    protected $attribute = [
        'real_name',
        'directory',
        'name',
        'size',
        'mime',
        'extension'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function init(array $data, array $thumbnail_list = [])
    {
        $directory = $data['directory'];
        $filename = $data['name'];
        $media = array_get(container('app'), 'config.app.host.media');
        $directory = $media . '/' . $directory . '/';

        foreach ($thumbnail_list as $thumbnail) {
            $thumbnail = 'thumbnail_' . $thumbnail;
            $data[$thumbnail] = $directory . $data[$thumbnail];
            $this->attribute[] = $thumbnail;
        }

        $attribute = array_merge($this->attribute, $thumbnail_list);

        $data = array_intersect_key($data, array_flip($attribute));
        $data['url'] = $directory . $filename;

        return $data;
    }
}
