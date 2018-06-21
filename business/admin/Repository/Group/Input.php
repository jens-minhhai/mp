<?php

namespace Admin\Repository\Group;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;

    protected $table = 'group';
    protected $fillable = [
        'title',
        'mode',
        'priority',
        'channel_id',
        'locale_id'
    ];

    public function add(array $data = [], int &$last_inserted_id = 0)
    {
        $data['channel_id'] = $this->channelId();
        $data['locale_id'] = $this->localeId();

        return parent::add($data, $last_inserted_id);
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
