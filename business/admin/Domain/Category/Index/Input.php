<?php

namespace Admin\Domain\Category\Index;

use Admin\Repository\Category\Index\Input as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Write;
use Request;

class Input extends Write
{
    use \Kernel\Traits\Domain\Relation\WriteTrait;

    protected $attribute = [
        'id',
        'title',
        'mode',
        'parent_id',
        'priority',
        'seo_id',
    ];

    public function withRelation()
    {
        $this->relation = [
            'seo' => [
                'master_field' => 'seo_id',
                'master_model' => Config::read('app.module.category'),
                'trigger' => Factory::service('seo.input'),
                'type' => DOMAIN_ASSOCIATION_PRIMARY
            ]
        ];
    }

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
        $this->withRelation();
    }

    protected function linkFormat(int $master_id)
    {
        $root = Request::param('root');

        return "{$root}/category/{$master_id}";
    }

    protected function saveSeo(
        array $data = [],
        int $master_id = 0,
        string $master_model = '',
        array $master = [],
        array $option = [],
        array &$error = []
    ) {
        $data['url'] = $this->linkFormat($master_id);
        extract($option);

        $prefix = Config::read('seo.prefix.' . Request::param('root'), '');

        $option = [
            'prefix' => $prefix
        ];

        $seo_id = empty($data['id']) ? 0 : $data['id'];

        $flag = $trigger->save($data, $seo_id, $master, $master_id, $master_model, $option, $error);

        if ($flag) {
            $update = [
                'seo_id' => $seo_id
            ];
            $this->with('where', 'id', $master_id)->update($update);
        }

        return $flag;
    }

    public function updateMode(array $id_list, int $mode, array &$error = [])
    {
        return $this->transaction(
            function () use ($id_list, $mode) {
                return $this->repository->updateMode($id_list, $mode);
            },
            function () use ($id_list, $mode, &$error) {
                $data = [
                    'seo' => [
                        'mode' => $mode
                    ]
                ];
                return $this->updateRelation($data, $id_list, [], $error);
            }
        );
    }

    public function updateSeo(
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
