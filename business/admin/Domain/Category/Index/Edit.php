<?php

namespace Admin\Domain\Category\Index;

use Kernel\Base\Domain\Read;
use Config;
use Factory;
use Admin\Repository\Category\Index\Index as Repository;

class Edit extends Read
{
    use \Kernel\Traits\Domain\Relation\ReadSingleTrait;

    protected $name = 'category';
    protected $attribute = [
        'id',
        'title',
        'mode',
        'parent_id',
        'priority',
        'seo_id'
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
        $this->withRelation();
    }

    public function withRelation()
    {
        $this->relation = [
            'seo' => [
                'master_field' => 'seo_id',
                'master_model' => Config::read('app.module.category'),
                'type' => DOMAIN_ASSOCIATION_PRIMARY,
                'trigger' => Factory::service('seo.index')
            ]
        ];
    }
}
