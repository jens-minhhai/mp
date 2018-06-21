<?php

namespace Service\Mail\Detail\Repository;

use Kernel\Base\Repository\Write;

class Logger extends Write
{
    use \Kernel\Traits\ChannelTrait;
    use \Kernel\Traits\LocaleTrait;

    protected $table = 'mail_detail';
    protected $fillable = [
        '_from',
        '_to',
        '_cc',
        '_bcc',
        'title',
        'content',
        'mode',
        'attemp',
        'delivery_time',
        'flush_time',
        'artisan',
        'driver',
        'channel_id',
        'locale_id'
    ];

    protected $escape = [
        'delivery_time' => 'raw',
        'flush_time' => 'raw'
    ];

    public function write(array $data = [], int &$last_inserted_id = 0)
    {
        $data['channel_id'] = $this->channelId();
        $data['locale_id'] = $this->localeId();

        return parent::add($data, $last_inserted_id);
    }

    protected function db()
    {
        return db()->shift()->from($this->table);
    }
}
