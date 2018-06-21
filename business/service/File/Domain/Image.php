<?php

namespace Service\File\Domain;

use Intervention\Image\ImageManagerStatic as ImageManager;

class Image extends File
{
    protected $attribute = [
        'id',
        'url',
    ];

    protected function generateThumbnail(array $info, array $option, array $thumb = [])
    {
        extract($option);
        $filename = $info['name'];
        $basename = $info['basename'];
        $extension = $info['extension'];

        $result = [];
        foreach ($thumb as $type => $size) {
            $target = ImageManager::make($destination . $filename);

            $target->resize($size[0], $size[1], function ($constraint) {
                $constraint->aspectRatio();
            });
            $thumb_name = $basename . '-' . $type . '.' . $extension;

            $target->save($destination . $thumb_name);

            $result['thumbnail_' . $type] = $url . $thumb_name;
        }

        return $result;
    }

    public function init(array $data, array $option, array $thumb = [])
    {
        $result = parent::init($data, $option);

        if ($thumb) {
            return array_merge($result, $this->generateThumbnail($data, $option, $thumb));
        }

        return $result;
    }
}
