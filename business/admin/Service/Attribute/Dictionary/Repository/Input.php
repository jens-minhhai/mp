<?php

namespace Admin\Service\Attribute\Dictionary\Repository;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;

    protected $pk = 'id';
    protected $table = 'attribute_dictionary';
    protected $fillable = [
        'name',
        'value',
        'target_model',
        'target_id',
        'channel_id',
        'locale_id'
    ];

    public function add(array $data = [], int &$last_inserted_id = 0)
    {
        $data['channel_id'] = $this->channelId();
        $data['locale_id'] = $this->localeId();

        return parent::add($data, $last_inserted_id);
    }

    protected function db()
    {
        return db()->shift()->from($this->table);
    }

    public function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];

        return $scope;
    }
}
