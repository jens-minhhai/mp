<?php

namespace Admin\Domain\Auth\Admin;

use Admin\Repository\Auth\Admin\Input as Repository;
use Factory;
use Kernel\Base\Domain\Write;
use Kernel\Lib\Security;

class Input extends Write
{
    protected $attribute = [
        'mode',
        'group_id',
        'account_id'
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function add(array $data, int &$id = 0)
    {
        return $this->transaction(
            function () use ($data, &$id) {
                $service = Factory::service('account.input');

                return $service->save($data, $id);
            },
            function () use ($data, &$id) {
                $data['account_id'] = $id;
                array_push($this->attribute, 'account', 'provider');
                if (isset($data['password'])) {
                    $data['password'] = Security::hash($data['password']);
                    array_push($this->attribute, 'password');
                }

                $id = 0;

                return parent::add($data, $id);
            }
        );
    }

    public function edit(array $data, int $auth_id)
    {
        return $this->transaction(
            function () use ($data, $auth_id) {
                return parent::edit($data, $auth_id);
            },
            function () use ($data, $auth_id) {
                $auth = Factory::load('domain.auth.admin.edit')->target($auth_id);

                return Factory::service('account.input')->edit($data, $auth['account_id']);
            }
        );
    }

    public function updateMode(array $id_list, int $mode, array &$error = [])
    {
        return $this->transaction(
            function () use ($id_list, $mode) {
                return $this->repository->updateMode($id_list, $mode);
            },
            function () use ($id_list, $mode, &$error) {
                $list = Factory::load('domain.auth.admin.edit')->getByIdList($id_list);
                $account_id_list = array_extract($list, '{n}.account_id');

                $data = [
                    'mode' => $mode
                ];

                return Factory::service('account.input')->updateByIdList($data, $account_id_list);
            }
        );
    }
}
