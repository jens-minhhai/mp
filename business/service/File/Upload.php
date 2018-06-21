<?php

namespace Service\File;

use Service\File\Domain\Upload as Domain;
use Factory;
use Kernel\Lib\Fs;

class Upload
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function upload(array $config, array &$info = [])
    {
        extract($config);

        $files = [];
        $destination = $location . $path . DIRECTORY_SEPARATOR;
        if (Fs::upload($destination, $files)) {
            $option = [
                'destination' => $destination,
                'url' => $url . $path . '/'
            ];

            $domain = $image ? 'file.domain.image' : 'file.domain.file';
            $domain = Factory::global_service($domain);
            foreach ($files as $index => $item) {
                $item_id = 0;

                $item = array_merge($item, ['directory' => $path]);
                $flag = $this->domain->add($item, $item_id);
                if (!$flag) {
                    return false;
                }

                $item['id'] = $item_id;
                $item = $domain->init($item, $option, $thumb);

                array_push($info, $item);
            }
        }

        return true;
    }
}
