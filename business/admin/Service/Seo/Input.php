<?php

namespace Admin\Service\Seo;

use Admin\Service\Seo\Domain\Input as Domain;

class Input
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function save(
        array $data,
        int &$seo_id,
        array $master,
        int $target_id,
        int $target_model,
        array $option = [],
        array &$error = []
    ) {
        $data = array_merge($data, compact('target_id', 'target_model'));
        return $this->domain->saveSeo($data, $master, $option, $seo_id, $error);
    }

    public function updateByTargetIdList(
        array $data,
        array $target_id,
        int $target_model,
        array &$error = []
    ) {
        return $this->domain->updateByTargetIdList($data, $target_id, $target_model);
    }
}
