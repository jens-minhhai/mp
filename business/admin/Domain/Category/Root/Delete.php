<?php

namespace Admin\Domain\Category\Root;

use Admin\Repository\Category\Root\Delete as Repository;
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
                'master_model' => Config::read('app.module.category'),
                'trigger' => Factory::service('code.delete'),
                'type' => DOMAIN_ASSOCIATION_FOREIGN
            ]
        ];
    }
}
