<?php

namespace Admin\Controller;

use Config;
use Factory;
use Request;

use Admin\Controller\Base;

class Group extends Base
{
    public function create()
    {
        $option = [
            'form' => $this->form(),
            'mode' => 'create',
            'base_url' => $this->baseUrl('group')
        ];

        return $this->render('group/input', $option);
    }

    public function postCreate()
    {
        $error = [];
        $id = 0;
        $target = Request::input('group');
        $flag = $this->makePostCreate($target, $id, $error);
        $this->notify($flag);
        
        if ($flag) {
            return $this->redirect($this->baseUrl('group', "/{$id}/edit"));
        }

        if ($error) {
            $this->set('error', $error, true);
        }

        return $this->create();
    }

    public function edit(int $id = 0)
    {
        $domain = $this->getDomain('edit');
        $target = $domain->target($id);

        if (empty($target)) {
            abort('404', 'page not found');
        }

        $option = [
            'form' => $this->form(),
            'mode' => 'edit',
            'target' => $target,
            'base_url' => $this->baseUrl('group')
        ];

        return $this->render('group/input', $option);
    }

    public function postEdit(int $id = 0)
    {
        $error = [];
        $target = Request::input('group');
        $flag = $this->makePostEdit($target, $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('group', "/{$id}/edit"));
        }

        if ($error) {
            $this->set('error', $error, true);
        }

        return $this->edit($id);
    }

    protected function makePostEdit(array $target, int $id = 0, array &$error = [])
    {
        $flag = $this->makeValidate($target);
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

    public function index()
    {
        $domain = $this->getDomain('read');

        $option = [
            'data' => $domain->get(),
            'form' => $this->form(),
            'base_url' => $this->baseUrl('group')
        ];

        return $this->render('group/index', $option);
    }

    public function postModify()
    {
        $target = Request::input('group');

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
        return [
            'master' => Config::read('group.master'),
        ];
    }

    protected function getDomain($mode)
    {
        switch ($mode) {
            case 'input':
                return Factory::load('domain.group.input');
            case 'read':
                return Factory::load('domain.group.index');
            case 'edit':
                return Factory::load('domain.group.edit');
            case 'delete':
                return Factory::load('domain.group.delete');
            case 'modify':
                return Factory::load('domain.group.input');
        }

        return null;
    }

    protected function makeValidate($target)
    {
        $validate = [
            'group' => [
                'group.input' => $target
            ]
        ];

        return $this->validate($validate);
    }
}
