<?php

namespace Admin\Domain\Post;

use Admin\Repository\Post\Index as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    use \Kernel\Traits\Domain\Relation\ReadMultipleTrait;
    use \Admin\Traits\Domain\Attribute\ReadMultipleTrait;

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
        $this->withAttr();
        $this->withRelation();
    }

    public function withRelation()
    {
        $this->relation = [
            'category' => [
                'type' => DOMAIN_ASSOCIATION_PRIMARY,
                'trigger' => Factory::global_service('category.collection'),
                'master_field' => 'category_id',
            ]
        ];
    }

    public function withAttr()
    {
        $this->attribute_model = Config::read('app.module.post');
        $this->attribute_enable = true;
        $this->attribute_virtual = [
            ATTRIBUTE_COLLECTION => [
                'obj2'
            ],
            // ATTRIBUTE_DICTIONARY => [
            //     'dic1',
            // ],
            // ATTRIBUTE_TEXT => [
            //     'text1',
            // ]
        ];
    }
}
