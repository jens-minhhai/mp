<?php

namespace Admin\Repository\Category\Root;

use Kernel\Base\Repository\Del;

class Delete extends Del
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;
    use \Admin\Traits\Repository\TreeTrait;

    protected $table = 'category';

    public function delete(array $id_list = [])
    {
        return $this->deleteTree($id_list);
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        return $scope;
    }
}
