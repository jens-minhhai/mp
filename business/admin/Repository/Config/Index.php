<?php

namespace Admin\Repository\Config;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Admin\Traits\ChannelTrait;

    protected $table = 'config';

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];

        return $scope;
    }

    public function get(array $field = [])
    {
        return $this->field($field)
                    ->order('code')
                    ->get();
    }
}
