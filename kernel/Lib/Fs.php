<?php

namespace Kernel\Lib;

class Fs
{
    public static function upload(string $destination = '', array &$info = [])
    {
        $files = container('request')->getUploadedFiles();

        foreach ($files as $index => $target) {
            $info_temp = [];
            $flag = self::saveFile($target, $destination, $info_temp);
            if (!$flag) {
                return false;
            }
            $info[$index] = $info_temp;
        }
        return true;
    }

    private static function saveFile($target, string $destination, array &$info = [])
    {
        if ($target->getError() !== UPLOAD_ERR_OK) {
            return false;
        }

        try {
            $origin_name = $target->getClientFilename();
            $extension = pathinfo($origin_name, PATHINFO_EXTENSION);
            $filename = pathinfo($origin_name, PATHINFO_FILENAME);

            $basename = $filename . '-' . time();
            $filename = $basename . '.' . $extension;

            if (!file_exists($destination)) {
                mkdir($destination, 0775, true);
            }
            $target->moveTo($destination . $filename);

            $info = [
                'real_name' => $target->getClientFilename(),
                'name' => $filename,
                'extension' => $extension,
                'size' => $target->getSize(),
                'mime' => $target->getClientMediaType(),
                'basename' => $basename,
            ];
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }
}
