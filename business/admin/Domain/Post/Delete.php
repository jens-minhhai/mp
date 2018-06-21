<?php

namespace Admin\Domain\Post;

use Admin\Repository\Post\Delete as Repository;
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
            'seo' => [
                'type' => DOMAIN_ASSOCIATION_PRIMARY,
                'trigger' => Factory::service('seo.delete'),
                'master_model' => Config::read('app.module.post')
            ]
        ];
    }
}
