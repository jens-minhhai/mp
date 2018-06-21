<?php

namespace Admin\Service\Attribute\Text\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;
    use \Kernel\Traits\Repository\Association\ReadTrait;

    protected $pk = 'id';
    protected $table = 'attribute_text';

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
