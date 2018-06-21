<?php

namespace Admin\Domain\Menu\Root;

use Admin\Repository\Menu\Root\Delete as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Del;

class Delete extends Del
{
    use \Kernel\Traits\Domain\Relation\DeleteTrait;

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
        $this->withRelation();
    }

    public function withRelation()
    {
        $this->relation = [
            'code' => [
                'master_model' => Config::read('app.module.menu'),
                'trigger' => Factory::service('code.delete'),
                'type' => DOMAIN_ASSOCIATION_FOREIGN
            ]
        ];
    }
}
