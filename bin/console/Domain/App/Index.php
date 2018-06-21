<?php

namespace Console\Domain\App;

use Console\Repository\App\Index as Repository;
use Console\Repository\App\Domain\Index as RepositoryAppDomain;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'token',
    ];

    public function __construct(Repository $repository, RepositoryAppDomain $app_domain)
    {
        parent::__construct($repository);
        $this->app_domain = $app_domain;
    }

    public function getTokenByDomain(string $domain)
    {
        $app_id = $this->app_domain->getAppIdByDomain($domain);
        if (!$app_id) {
            abort(404);
        }
        $app_id = array_first($app_id);
        $target = $this->repository->target($app_id, $this->attribute);
        if (!$target) {
            abort(404);
        }
        return array_first($target);
    }
}
