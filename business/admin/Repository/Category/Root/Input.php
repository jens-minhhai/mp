<?php

namespace Admin\Repository\Category\Root;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;
    use \Admin\Traits\Repository\TreeTrait;

    protected $table = 'category';
    protected $fillable = [
        'title',
        'mode',
        'tree_id'
    ];

    public function add(array $data = [], int &$last_inserted_id = 0)
    {
        $data = array_mask($data, $this->fillable);
        $data['channel_id'] = $this->channelId();
        $data['locale_id'] = $this->localeId();

        return $this->initTree($data, $last_inserted_id);
    }

    public function updateMode(array $id_list, int $mode)
    {
        $data = [
            'mode' => $mode
        ];

        $data = $this->master($data, REPOSITORY_MODE_UPDATE);

        return $this->in($this->pk, $id_list)
                    ->update($data);
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        return $scope;
    }
}
