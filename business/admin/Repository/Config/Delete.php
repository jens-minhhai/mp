<?php

namespace Admin\Repository\Config;

use Kernel\Base\Repository\Del;

class Delete extends Del
{
    use \Admin\Traits\ChannelTrait;

    protected $table = 'config';

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];

        return $scope;
    }
}
