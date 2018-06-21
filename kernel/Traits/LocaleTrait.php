<?php

namespace Kernel\Traits;

use Request;

trait LocaleTrait
{
    private function localeId()
    {
        return Request::get('locale');
    }
}
