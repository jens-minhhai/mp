<?php

namespace Admin\Domain\Auth\Admin;

use Admin\Repository\Auth\Admin\Delete as Repository;
use Factory;
use Kernel\Base\Domain\Del;

class Delete extends Del
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function delete(array $id_list)
    {
        return $this->transaction(
            function () use ($id_list, &$error) {
                $list = Factory::load('domain.auth.admin.edit')->getByIdList($id_list);
                $account_id_list = array_extract($list, '{n}.account_id');

                return Factory::service('account.delete')->delete($account_id_list);
            },
            function () use ($id_list, &$error) {
                return $this->repository->delete($id_list);
            }
        );
    }
}
