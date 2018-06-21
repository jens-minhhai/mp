<?php

namespace Kernel\Traits\Repository;

trait MasterTrait
{
    protected function master(array $data = [], int $mode = REPOSITORY_MODE_CREATE)
    {
        switch ($mode) {
            case REPOSITORY_MODE_CREATE:
                $data['creator'] = auth_id();
                $data['app_id'] = app_id();
                break;
            case REPOSITORY_MODE_UPDATE:
                $data['editor'] = auth_id();
                break;
            case REPOSITORY_MODE_DELETE:
                $data['deleted_by'] = auth_id();
                break;
            default:
                break;
        }

        return $data;
    }

    protected function track()
    {
        return auth('id');
    }
}
