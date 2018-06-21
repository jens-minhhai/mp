<?php

namespace Service\Mail\Template\Repository;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    use \Kernel\Traits\ChannelTrait;
    use \Kernel\Traits\LocaleTrait;

    protected $table = 'mail_template';

    public function getByName(string $name, array $field = [])
    {
        return $this->field($field)
                     ->where('name', $name)
                     ->where('mode', ENABLE)
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
        return db()->shift()
                   ->from($this->table);
    }
}
