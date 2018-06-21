<?php

namespace Admin\Domain\Category\Root;

use Admin\Repository\Category\Root\Index as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Read;

class Edit extends Read
{
    use \Kernel\Traits\Domain\Relation\ReadSingleTrait {
        targetWithRelation as targetWithRelation2;
    }

    protected $attribute = [
        'id',
        'title',
        'mode'
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
                'master_model' => Config::read('app.module.category'),
                'trigger' => Factory::service('code.index'),
                'type' => DOMAIN_ASSOCIATION_FOREIGN,
                'unique' => true
            ]
        ];
    }

    public function targetWithRelation(int $id = 0)
    {
        $target = $this->targetWithRelation2($id);
        if ($target) {
            $target['name'] = $target['name']['code'];
        }

        return $target;
    }
}
