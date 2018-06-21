<?php

namespace Admin\Service\Code\Repository;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;

    protected $pk = 'id';
    protected $table = 'code';
    protected $fillable = [
        'code',
        'mode',
        'channel_id',
        'locale_id',
        'target_model',
        'target_id',
    ];

    public function add(array $data = [], int &$last_inserted_id = 0)
    {
        $data['channel_id'] = $this->channelId();
        $data['locale_id'] = $this->localeId();

        return parent::add($data, $last_inserted_id);
    }

    public function updateByTargetIdList(array $data = [], array $target_id_list = [], int $target_model = 0)
    {
        return $this->with('in', 'target_id', $target_id_list)
                    ->with('where', 'target_model', $target_model)
                    ->update($data);
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];

        return $scope;
    }
}
