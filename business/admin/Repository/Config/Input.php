<?php

namespace Admin\Repository\Config;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    use \Admin\Traits\ChannelTrait;

    protected $table = 'config';
    protected $fillable = [
        'code',
        'value',
        'channel_id',
        'mode'
    ];

    public function add(array $data = [], int &$last_inserted_id = 0)
    {
        $data['channel_id'] = $this->channelId();
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

        return $scope;
    }
}
