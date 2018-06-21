<?php

namespace Admin\Service\Attribute\Collection\Repository;

use Kernel\Base\Repository\Del;

class Delete extends Del
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;
    use \Kernel\Traits\Repository\Association\DeleteTrait;

    protected $table = 'attribute_collection';

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];

        return $scope;
    }

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
