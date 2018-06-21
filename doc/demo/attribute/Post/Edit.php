<?php

namespace Admin\Domain\Post;

use Admin\Repository\Post\Index as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Read;

class Edit extends Read
{
    use \Admin\Traits\Domain\Attribute\ReadSingleTrait;
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
        $this->withAttr();
    }

    public function withRelation()
    {
        $this->relation = [
            'seo' => [
                'type' => DOMAIN_ASSOCIATION_PRIMARY,
                'trigger' => Factory::service('seo.index'),
                'master_field' => 'seo_id'
            ]
        ];
    }

    public function withAttr()
    {
        $this->attribute_model = Config::read('app.module.post');
        $this->attribute_enable = true;
        $this->attribute_virtual = [
            // ATTRIBUTE_DICTIONARY => [
            //     'dic1',
            //     'dic2',
            // ],
            ATTRIBUTE_COLLECTION => [
                'obj1',
                // 'obj2'
            ],
            // ATTRIBUTE_TEXT => [
            //     'text1',
            //     'text2'
            // ]
        ];
    }
}
