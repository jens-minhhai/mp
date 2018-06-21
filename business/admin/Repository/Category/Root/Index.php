<?php

namespace Admin\Repository\Category\Root;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;
    use \Admin\Traits\Repository\TreeTrait;

    protected $table = 'category';

    public function get(array $field = [])
    {
        return $this->field($field)
                    ->where($this->parent, 0)
                    ->order('id', 'desc')
                    ->get();
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        return $scope;
    }
}
