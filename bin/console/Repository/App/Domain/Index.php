<?php

namespace Console\Repository\App\Domain;

use Kernel\Base\Repository\Read;

class Index extends Read
{
    protected $table = 'app_domain';

    public function getAppIdByDomain(string $domain)
    {
        return $this->select('app_id')
                    ->where('domain', $domain)
                    ->first();
    }

    protected function scope()
    {
        return [
            'alive' => ['alive'],
        ];
    }
}
