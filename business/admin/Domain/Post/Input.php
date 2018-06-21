<?php

namespace Admin\Domain\Post;

use Admin\Repository\Post\Input as Repository;
use Config;
use Factory;
use Kernel\Base\Domain\Write;

class Input extends Write
{
    use \Kernel\Traits\Domain\Relation\WriteTrait;

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
                'trigger' => Factory::service('seo.input'),
                'master_field' => 'seo_id',
                'master_model' => Config::read('app.module.post')
            ]
        ];
    }

    protected function linkFormat(int $master_id)
    {
        return "post/detail/{$master_id}";
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

        $option = [
            'prefix' => Config::read('seo.prefix.post', 'post')
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
        array $update,
        array $master_id_list,
        int $master_model,
        array $master,
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
