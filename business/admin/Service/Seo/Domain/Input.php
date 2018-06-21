<?php

namespace Admin\Service\Seo\Domain;

use Admin\Service\Seo\Repository\Input as Repository;
use Config;
use Kernel\Base\Domain\Write;

class Input extends Write
{
    protected $attribute = [
        'id',
        'alias',
        'url',
        'canonical',
        'title',
        'keyword',
        'description',
        'priority',
        'mode',
        'target_model',
        'target_id'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function updateByTargetIdList(array $data, array $target_id_list, int $target_model, array &$error = [])
    {
        $data = array_mask($data, $this->attribute);

        return $this->repository->updateByTargetIdList($data, $target_id_list, $target_model);
    }

    public function saveSeo(array $data = [], array $master = [], array $option = [], int &$id = 0, &$error = [])
    {
        $data = $this->format($data, $master, $option);
        $data = array_mask($data, $this->attribute);

        return $this->repository->save($data, $id);
    }

    public function format(array $data, array $master = [], array $option = [])
    {
        $data['alias'] = $this->generateAlias($data, $master, $option);

        if (empty($data['canonical'])) {
            $data['canonical'] = $data['alias'] . '/';
        }
        $data['canonical'] = strtolower($data['canonical']);

        if (empty($data['title'])) {
            $data['title'] = $master['title'];
        }

        if (empty($data['keyword'])) {
            $data['keyword'] = str_replace(' ', ', ', mb_strtolower($master['title']));
        }

        if (empty($data['description'])) {
            $run = ['content', 'title'];

            $content = '';
            foreach ($run as $key) {
                if (empty($master[$key])) {
                    continue;
                }

                $content .= strip_tags($master[$key]) . ' ';
            }

            $data['description'] = $content;
        }

        $data['description'] = str_limit($data['description'], 150, '');

        return $data;
    }

    private function generateAlias($data, $reference, array $option = [])
    {
        $alias = $data['alias'] ?? '';
        if (empty($alias)) {
            $alias = $option['prefix'] .
                    '/' .
                    str_slug($reference['title']);
        }

        $similar = $this->repository->getSimilarByAlias($alias, $data);

        if ($similar) {
            $alias .= '-' . mb_strtolower(str_unique());
        }

        return strtolower(trim($alias, '/'));
    }
}
