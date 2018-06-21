<?php

namespace Admin\Repository\Menu\Root;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;
    use \Admin\Traits\Repository\TreeTrait;

    protected $table = 'menu';
    protected $fillable = [
        'title',
        'mode',
        'tree_id',
        'locale_id',
        'channel_id'
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

        return $this->with('in', 'id', $id_list)->update($data);
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];

        return $scope;
    }
}
