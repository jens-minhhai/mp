<?php

namespace Admin\Controller;

use Admin\Controller\Base;
use Config;
use Factory;

class File extends Base
{
    public function postUpload()
    {
        $config = [
            'path' => date('y/m'),
            'url' => Config::read('app.host.media') . '/',
            'location' => ROOT . DIRECTORY_SEPARATOR . env('APP_UPLOAD_PATH'),
            'image' => true,
            'thumb' => [
                'small' => [120, 120],
                'medium' => [300, 300],
                'large' => [360, 360],
            ]
        ];

        $file_list = [];
        Factory::global_service('file.upload')->upload($config, $file_list);
        return $this->json($file_list);
    }

    public function delete(int $id)
    {
        db()->begin();

        $flag = Factory::global_service('file.delete')->delete([$id]);
        $result = [
            'result' => $flag ? 'true' : 'false',
            'id' => $id
        ];
        return $this->json($result);
    }
}
