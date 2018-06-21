<?php

namespace Admin\Traits;

use Config;
use Request;

trait ChannelTrait
{
    private function channelId()
    {
        $channel = Request::param('channel');
        
        if ($channel) {
            return Config::read('app.channel.' . $channel);
        }
        
        return Config::anonymous('channel', false);
    }
}
