<?php

namespace Admin\Domain\Auth\Admin;

use Admin\Repository\Auth\Admin\Index as Repository;
use Factory;
use Kernel\Base\Domain\Read;

class Edit extends Read
{
    use \Kernel\Traits\Domain\Relation\ReadSingleTrait {
        targetWithRelation as targetWithRelation2;
    }

    protected $attribute = [
        'id',
        'account',
        'group_id',
        'account_id',
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
            'acc' => [
                'trigger' => Factory::service('account.index'),
                'type' => DOMAIN_ASSOCIATION_PRIMARY,
                'master_field' => 'account_id'
            ]
        ];
    }

    public function targetWithRelation(int $id)
    {
        $target = $this->targetWithRelation2($id);

        $acc = $target['acc'];
        unset($acc['id'], $target['acc']);

        return array_merge($target, $acc);
    }
}
