<?php

namespace Service\Menu\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Kernel\Traits\ChannelTrait;
    use \Kernel\Traits\LocaleTrait;
    use \Kernel\Traits\Repository\TreeTrait;

    protected $table = 'menu';
    
    protected function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];
        return $scope;
    }
}
