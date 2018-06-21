<?php

namespace Admin\Controller;

use Config;
use Factory;
use Request;

use Admin\Controller\Base;

class Message extends Base
{
    public function create()
    {
        $option = [
            'form' => $this->form(),
            'mode' => 'create',
            'base_url' => $this->baseUrl('message')
        ];

        return $this->render('message/input', $option);
    }

    public function postCreate()
    {
        $error = [];
        $id = 0;
        $target = Request::input('message');
        $flag = $this->makePostCreate($target, $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('message', "/{$id}/edit"));
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
            'base_url' => $this->baseUrl('message')
        ];

        return $this->render('message/input', $option);
    }

    public function postEdit(int $id = 0)
    {
        $error = [];
        $target = Request::input('message');
        $flag = $this->makePostEdit($target, $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('message', "/{$id}/edit"));
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
            'base_url' => $this->baseUrl('message')
        ];

        return $this->render('message/index', $option);
    }

    public function postModify()
    {
        $target = Request::input('message');

        $id_list = $target['id'] ?? [];
        $operator = $target['option'] ?? 0;

        if ($operator == UPDATE) {
            return $this->makeUpdate($id_list, $target);
        }

        if ($operator == DELETE) {
            return $this->makeDelete($id_list);
        }

        $modify = [ENABLE, DISABLE];
        if (in_array($operator, $modify)) {
            return $this->makeUpdateMode($id_list, $operator);
        }

        return $this->back();
    }

    protected function makeUpdate(array $id_list, array $data)
    {
        if ($id_list) {
            $domain = $this->getDomain('modify');
            $flag = $domain->modify($id_list, $data);
            $this->notify($flag);
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
            'master' => Config::read('message.master'),
        ];
    }

    protected function getDomain($mode)
    {
        switch ($mode) {
            case 'input':
                return Factory::load('domain.message.input');
            case 'read':
                return Factory::load('domain.message.index');
            case 'edit':
                return Factory::load('domain.message.edit');
            case 'delete':
                return Factory::load('domain.message.delete');
            case 'modify':
                return Factory::load('domain.message.input');
        }

        return null;
    }

    protected function makeValidate($target)
    {
        $validate = [
            'message' => [
                'message.input' => $target
            ]
        ];

        return $this->validate($validate);
    }
}
