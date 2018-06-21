<?php

namespace Admin\Domain\Category\Root;

use Admin\Repository\Category\Root\Input as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Write;

class Input extends Write
{
    use \Kernel\Traits\Domain\Relation\WriteTrait;

    protected $attribute = [
        'id',
        'title',
        'mode',
    ];

    public function withRelation()
    {
        $this->relation = [
            'code' => [
                'force' => true,
                'master_model' => Config::read('app.module.category'),
                'trigger' => Factory::service('code.input')
            ]
        ];
    }

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
        $this->withRelation();
    }

    protected function saveCode(
        array $data = [],
        int $master_id = 0,
        string $master_model = '',
        array $master = [],
        array $option = [],
        array &$error = []
    ) {
        $code_id = 0;

        extract($option);
        $data = [
            'code' => $master['name'] ?: $master['title'],
        ];

        return $trigger->save($data, $master_id, $master_model, $code_id, $error);
    }

    public function updateMode(array $id_list, int $mode, array &$error = [])
    {
        return $this->transaction(
            function () use ($id_list, $mode) {
                return $this->repository->updateMode($id_list, $mode);
            },
            function () use ($id_list, $mode, &$error) {
                $data = [
                    'code' => [
                        'mode' => $mode
                    ]
                ];
                return $this->updateRelation($data, $id_list, [], $error);
            }
        );
    }

    public function updateCode(
        array $update = [],
        array $master_id_list = [],
        string $master_model = '',
        array $master = [],
        array $option = [],
        array &$error = []
    ) {
        extract($option);

        return $trigger->updateByTargetIdList(
                $update,
                $master_id_list,
                $master_model,
                $error
            );
    }
}
