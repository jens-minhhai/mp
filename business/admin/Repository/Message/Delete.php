<?php

namespace Admin\Repository\Message;

use Kernel\Base\Repository\Del;

class Delete extends Del
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;

    protected $table = 'message';

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];

        return $scope;
    }
}
