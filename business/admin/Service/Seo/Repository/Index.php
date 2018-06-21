<?php

namespace Admin\Service\Seo\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;

    protected $pk = 'id';
    protected $table = 'seo';

    public function target(int $id, array $field = [])
    {
        return $this->field($field)
                    ->where($this->pk, $id)
                    ->alive()
                    ->first();
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];

        return $scope;
    }
}
