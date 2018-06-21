<?php

namespace Service\Code\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Kernel\Traits\ChannelTrait;
    use \Kernel\Traits\LocaleTrait;
    use \Kernel\Traits\Repository\Association\ReadTrait;

    protected $pk = 'id';
    protected $table = 'code';

    public function getByCode(string $code, int $model, array $field)
    {
        return $this->field($field)
                    ->where('code', $code)
                    ->where('target_model', $model)
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
