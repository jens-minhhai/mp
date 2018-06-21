<?php

namespace Console\Controller\Master;

use Factory;
use Log;
use Kernel\Lib\Security;

class Release
{
    public function __construct()
    {
        load('config/master/constant.php', BIN);
    }

    public function execute()
    {
        // db()->begin();
        Factory::load('domain.master.release')->execute();
        Log::debug(print_r(db()->log(), true));

        return true;
    }
}
