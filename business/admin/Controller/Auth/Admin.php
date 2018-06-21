<?php

namespace Admin\Controller\Auth;

use Config;
use Factory;
use Request;

use Admin\Controller\Base;

class Admin extends Base
{
    public function index()
    {
        $domain = $this->getDomain('read');

        $option = [
            'base_url' => $this->baseUrl('auth'),
            'data' => $domain->getWithRelation(),
            'form' => $this->form(),
            'mode' => Config::read('auth.admin.master.mode')
        ];

        return $this->render('auth/admin/index', $option);
    }

    public function create()
    {
        $option = [
            'form' => $this->form(),
            'mode' => 'create',
            'base_url' => $this->baseUrl('auth')
        ];

        return $this->render('auth/admin/add', $option);
    }

    public function postCreate()
    {
        $error = [];
        $id = 0;
        $target = Request::input('admin');

        $flag = $this->makePostCreate($target, $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('auth', "/{$id}/edit"));
        }

        if ($error) {
            $this->set('error', $error, true);
        }

        return $this->create();
    }

    public function edit(int $id = 0)
    {
        $domain = $this->getDomain('edit');
        $target = $domain->targetWithRelation($id);

        if (empty($target)) {
            abort('404', 'page not found');
        }

        $option = [
            'form' => $this->form(),
            'mode' => 'edit',
            'target' => $target,
            'base_url' => $this->baseUrl('auth')
        ];

        return $this->render('auth/admin/edit', $option);
    }

    public function postEdit(int $id = 0)
    {
        $error = [];
        $target = Request::input('admin');
        $flag = $this->makePostEdit($target, $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('auth', "/{$id}/edit"));
        }

        if ($error) {
            $this->set('error', $error, true);
        }

        return $this->edit($id);
    }

    public function postModify()
    {
        $target = Request::input('admin');

        $id_list = $target['id'] ?? [];
        $operator = $target['option'] ?? 0;

        if ($operator == DELETE) {
            return $this->makeDelete($id_list);
        }

        $modify = [ENABLE, DISABLE];
        if (in_array($operator, $modify)) {
            return $this->makeUpdateMode($id_list, $operator);
        }

        return $this->back();
    }

    protected function makePostEdit(array $target, int $id = 0, array &$error = [])
    {
        $flag = $this->makeValidate($target, 'edit');
        if ($flag) {
            $domain = $this->getDomain('input');

            return $domain->edit($target, $id, $error);
        }

        return false;
    }

    protected function makePostCreate(array $target, int &$id = 0, array &$error = [])
    {
        $flag = $this->makeValidate($target);

        if ($flag) {
            $domain = $this->getDomain('input');

            return $domain->add($target, $id, $error);
        }

        return false;
    }

    protected function makeDelete(array $id_list = [])
    {
        if ($id_list) {
            $domain = $this->getDomain('delete');
            $flag = $domain->delete($id_list);

            $this->notify($flag, DELETE);
        }

        return $this->back();
    }

    protected function makeUpdateMode(array $id_list, int $mode)
    {
        if ($id_list) {
            $domain = $this->getDomain('modify');
            $flag = $domain->updateMode($id_list, $mode);
            $this->notify($flag);
        }

        return $this->back();
    }

    protected function form()
    {
        $group_list = Factory::service('group.index')->get();

        return [
            'group' => array_pluck($group_list, '{n}.id', '{n}.title'),
            'master' => Config::read('auth.admin.master')
        ];
    }

    protected function getDomain($mode)
    {
        switch ($mode) {
            case 'input':
                return Factory::load('domain.auth.admin.input');
            case 'read':
                return Factory::load('domain.auth.admin.index');
            case 'edit':
                return Factory::load('domain.auth.admin.edit');
            case 'delete':
                return Factory::load('domain.auth.admin.delete');
            case 'modify':
                return Factory::load('domain.auth.admin.input');
        }

        return null;
    }

    protected function makeValidate(array $target, string $type = 'add')
    {
        $validate = [
            'auth' => [
                'auth.admin.' . $type => $target
            ]
        ];

        return $this->validate($validate);
    }

    protected function modifiedRule(array $rule, array $target)
    {
        $auth_id = $target['id'] ?: 0;
        $rule['account'][1] .= '@' . $auth_id;
        $rule['email'][2] .= '@' . $auth_id;

        return $rule;
    }
}
