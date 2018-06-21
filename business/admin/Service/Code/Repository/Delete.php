<?php

namespace Admin\Service\Code\Repository;

use Kernel\Base\Repository\Del;

class Delete extends Del
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;

    protected $table = 'code';

    public function deleteByTargetId(array $target_id, int $target_model)
    {
        return $this->where('target_model', $target_model)
                    ->in('target_id', $target_id)
                    ->delete($this->track());
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];

        return $scope;
    }

    protected function db()
    {
        return db()->shift()
                   ->from($this->table);
    }
}
