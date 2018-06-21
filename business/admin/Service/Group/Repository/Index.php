<?php

namespace Admin\Service\Group\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Kernel\Traits\ChannelTrait;

    protected $pk = 'id';
    protected $table = 'group';

    protected function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];

        return $scope;
    }

    protected function db()
    {
        return db()->shift()
                   ->from($this->table);
    }
}
