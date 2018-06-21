<?php

namespace Console\Domain\Master;

use Factory;
use Kernel\Base\Domain\Read;

class Release extends Read
{
    public function execute()
    {
        $this->cleanup();

        return $this->progress();
    }

    private function progress()
    {
        $progress = [
            'app',
            'appDomain',
            'account',
            'group',
            'auth',
            'seo',
            'config',
            'message',
            'category',
        ];

        foreach ($progress as $item) {
            if (Factory::load('repository.master.' . $item)->release()) {
                continue;
            }

            return false;
        }

        return true;
    }

    private function cleanup()
    {
        $table = [
            'app',
            'app_domain',
            'account',
            'group',
            'auth',
            'seo',
            'config',
            'message',
            'category',
            'code',
        ];
        $repo = Factory::load('repository.master.base');
        foreach ($table as $target) {
            $repo->truncate($target);
        }
    }
}
