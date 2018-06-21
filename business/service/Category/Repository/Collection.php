<?php

namespace Service\Category\Repository;

use Kernel\Base\Repository\Read;

class Collection extends Read
{
    use \Kernel\Traits\ChannelTrait;
    use \Kernel\Traits\LocaleTrait;
    use \Kernel\Traits\Repository\TreeTrait;

    protected $pk = 'id';
    protected $table = 'category';

    public function scope()
    {
        $scope = parent::scope();
        $scope['mode'] = ['where', 'mode', ENABLE];
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];
        
        return $scope;
    }

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
