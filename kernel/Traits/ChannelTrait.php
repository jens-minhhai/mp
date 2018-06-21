<?php

namespace Kernel\Traits;

use Request;

trait ChannelTrait
{
    private function channelId()
    {
        return Request::get('channel');
    }
}
