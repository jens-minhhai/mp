<?php

namespace Admin\Domain\Menu\Root;

use Admin\Repository\Menu\Root\Index as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    use \Kernel\Traits\Domain\Relation\ReadMultipleTrait {
        getWithRelation as getWithRelation2;
    }

    protected $attribute = [
        'id',
        'title',
        'mode',
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
        $this->withRelation();
    }

    public function withRelation()
    {
        $this->relation = [
            'name' => [
                'type' => DOMAIN_ASSOCIATION_FOREIGN,
                'trigger' => Factory::service('code.index'),
                'unique' => true,
                'master_model' => Config::read('app.module.menu')
            ]
        ];
    }

    public function getWithRelation()
    {
        $data = $this->getWithRelation2();

        foreach ($data as &$target) {
            $target['name'] = $target['name']['code'];
        }

        return $data;
    }
}
