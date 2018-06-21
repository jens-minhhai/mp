<?php

namespace Service\Seo\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Kernel\Traits\ChannelTrait;
    use \Kernel\Traits\LocaleTrait;
    
    protected $pk = 'id';
    protected $table = 'seo';

    public function target(int $id, array $field = [])
    {
        return $this->field($field)
                    ->where($this->pk, $id)
                    ->alive()
                    ->first();
    }
    
    protected function scope()
    {
        $scope = parent::scope();
        $scope['channel_id'] = ['where', 'channel_id', $this->channelId()];
        $scope['locale_id'] = ['where', 'locale_id', $this->localeId()];
        return $scope;
    }
    
    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
