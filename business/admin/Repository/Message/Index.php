<?php

namespace Admin\Repository\Message;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;

    protected $table = 'message';

    public function get(array $field = [])
    {
        return $this->field($field)
                    ->order('code')
                    ->get();
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];

        return $scope;
    }
}
