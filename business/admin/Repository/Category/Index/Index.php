<?php

namespace Admin\Repository\Category\Index;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;
    use \Admin\Traits\Repository\TreeTrait;

    protected $table = 'category';

    public function scope()
    {
        $scope = parent::scope();
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        return $scope;
    }
}
