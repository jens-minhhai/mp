<?php

namespace Admin\Service\Seo\Repository;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;

    protected $pk = 'id';
    protected $table = 'seo';
    protected $fillable = [
        'alias',
        'url',
        'canonical',
        'title',
        'keyword',
        'description',
        'priority',
        'mode',
        'target_model',
        'target_id',
        'locale_id',
        'channel_id'
    ];

    public function updateByTargetIdList(array $data, array $target_id_list, int $target_model)
    {
        return $this->with('in', 'target_id', $target_id_list)
                    ->with('where', 'target_model', $target_model)
                    ->update($data);
    }

    public function add(array $data, int &$last_inserted_id = 0)
    {
        $data['channel_id'] = $this->channelId();
        $data['locale_id'] = $this->localeId();

        return parent::add($data, $last_inserted_id);
    }

    public function getSimilarByAlias(string $alias, array $target, $channel = CHANNEL_PUBLIC)
    {
        if ($target['id']) {
            return $this->select('id')
                        ->not('id', $target['id'])
                        ->where('alias', $alias)
                        ->where('channel_id', $channel)
                        ->first();
        }

        return [];
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];

        return $scope;
    }
}
