<?php

namespace Admin\Traits;

use Config;
use Request;

trait LocaleTrait
{
    private function localeId()
    {
        $locale = Request::param('locale');

        if ($locale) {
            return Config::read('app.locale.' . $locale);
        }

        return Config::anonymous('locale', false);
    }
}
