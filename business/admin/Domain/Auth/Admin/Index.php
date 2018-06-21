<?php

namespace Admin\Domain\Auth\Admin;

use Admin\Repository\Auth\Admin\Index as Repository;
use Factory;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    use \Kernel\Traits\Domain\Relation\ReadMultipleTrait {
        getWithRelation as getWithRelation2;
    }

    protected $attribute = [
        'id',
        'account',
        'group_id',
        'provider',
        'mode',
        'account_id'
    ];

    protected $attribute2 = [
        'id',
        'account',
        'group_id',
        'provider',
        'mode',
        'account_id',
        'fullname',
        'email',
        'group',
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
        $this->withRelation();
    }

    public function withRelation()
    {
        $this->relation = [
            'acc' => [
                'master_field' => 'account_id',
                'trigger' => Factory::service('account.index'),
                'type' => DOMAIN_ASSOCIATION_PRIMARY,
            ],
            'group' => [
                'master_field' => 'group_id',
                'trigger' => Factory::service('group.index'),
                'type' => DOMAIN_ASSOCIATION_PRIMARY,
            ]
        ];
    }

    public function getWithRelation()
    {
        return array_map(function ($item) {
            unset($item['acc']['id']);
            $item = array_merge($item, $item['acc']);
            $item['group'] = $item['group']['title'];

            unset($item['acc']);

            return array_mask($item, $this->attribute2);
        }, $this->getWithRelation2());
    }
}
