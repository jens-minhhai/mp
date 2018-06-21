<?php

namespace Service\File\Domain;

class File
{
    protected $attribute = [
        'id',
        'url',
    ];

    public function init(array $data, array $option)
    {
        $data['url'] = $option['url'] . $data['name'];
        return array_mask($data, $this->attribute);
    }
}
