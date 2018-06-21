<?php

namespace Admin\Domain\Post;

use Admin\Repository\Post\Index as Repository;
use Factory;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    use \Kernel\Traits\Domain\Relation\ReadMultipleTrait;

    protected $attribute = [
        'id',
        'category_id',
        'title',
        'mode',
        'priority',
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
        $this->withRelation();
    }

    public function withRelation()
    {
        $this->relation = [
            'category' => [
                'type' => DOMAIN_ASSOCIATION_PRIMARY,
                'trigger' => Factory::service('category.collection'),
                'master_field' => 'category_id',
            ]
        ];
    }
}
