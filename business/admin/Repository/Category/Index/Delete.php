<?php

namespace Admin\Repository\Category\Index;

use Kernel\Base\Repository\Del;

class Delete extends Del
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;
    use \Admin\Traits\Repository\TreeTrait;

    protected $table = 'category';

    public function delete(array $id_list = [])
    {
        $deleted = [];
        foreach ($id_list as $id) {
            if (in_array($id, $deleted)) {
                continue;
            }

            $target_id_list = $this->extract($id, ['id']);

            $flag = $this->deleteNode($id);
            if (!$flag) {
                return false;
            }

            $deleted = array_merge($deleted, array_extract($target_id_list, '{n}.id'));
        }

        return true;
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        return $scope;
    }
}
