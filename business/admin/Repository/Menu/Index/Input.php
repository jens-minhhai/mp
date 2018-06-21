<?php

namespace Admin\Repository\Menu\Index;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    use \Admin\Traits\ChannelTrait;
    use \Admin\Traits\LocaleTrait;
    use \Admin\Traits\Repository\TreeTrait;

    protected $table = 'menu';
    protected $fillable = [
        'title',
        'url',
        'target',
        'caption',
        'mode',
        'parent_id',
        'priority'
    ];

    public function add(array $data = [], int &$last_inserted_id = 0)
    {
        $data = array_mask($data, $this->fillable);
        $data['channel_id'] = $this->channelId();
        $data['locale_id'] = $this->localeId();

        return $this->addNode($data, $last_inserted_id);
    }

    public function edit(array $data = [], int $target = 0)
    {
        $data = array_mask($data, $this->fillable);

        return $this->editNode($data, $target);
    }

    public function updateMode(array $id_list, int $mode)
    {
        $target = [];
        foreach ($id_list as $id) {
            $tmp = $this->extract($id, ['id']);
            $target = array_merge($target, array_extract($tmp, '{n}.id'));
        }

        $data = [
            'mode' => $mode
        ];
        return $this->with('in', 'id', array_unique($target))
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
