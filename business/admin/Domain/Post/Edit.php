<?php

namespace Admin\Domain\Post;

use Admin\Repository\Post\Index as Repository;
use Factory;
use Kernel\Base\Domain\Read;

class Edit extends Read
{
    use \Kernel\Traits\Domain\Relation\ReadSingleTrait;

    protected $attribute = [
        'id',
        'category_id',
        'title',
        'content',
        'mode',
        'priority',
        'file_id',
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
                'type' => DOMAIN_ASSOCIATION_PRIMARY,
                'trigger' => Factory::service('seo.index'),
                'master_field' => 'seo_id'
            ],
            'file' => [
                'type' => DOMAIN_ASSOCIATION_PRIMARY,
                'trigger' => Factory::global_service('file.link'),
                'master_field' => 'file_id'
            ]
        ];
    }
}
