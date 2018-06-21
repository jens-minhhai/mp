<?php

namespace Admin\Controller\Menu;

use Config;
use Factory;
use Request;

use Admin\Controller\Base;

class Root extends Base
{
    public function create()
    {
        $option = [
            'form' => $this->form(),
            'mode' => 'create',
            'base_url' => $this->baseUrl('menu')
        ];

        return $this->render($this->template('input'), $option);
    }

    public function postCreate()
    {
        $target = Request::input('menu');

        $error = [];
        $id = 0;
        $flag = $this->makePostCreate($target, $id, $error);

        $this->notify($flag);
        if ($flag) {
            return $this->redirect($this->baseUrl('menu', "/root/{$id}/edit"));
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
            'base_url' => $this->baseUrl('menu')
        ];

        return $this->render($this->template('input'), $option);
    }

    public function postEdit(int $id = 0)
    {
        $target = Request::input('menu');

        $error = [];
        $flag = $this->makePostEdit($target, $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('menu', "/root/{$id}/edit"));
        }

        if ($error) {
            $this->set('error', $error, true);
        }

        return $this->edit($id);
    }

    public function index()
    {
        $domain = $this->getDomain('read');

        $option = [
            'data' => $domain->getWithRelation(),
            'form' => $this->form(),
            'base_url' => $this->baseUrl('menu')
        ];

        return $this->render($this->template('index'), $option);
    }

    public function postModify()
    {
        $target = Request::input('menu');
        $id_list = $target['id'] ?? [];
        $operator = $target['option'] ?: 0;

        if ($operator == DELETE) {
            return $this->makeDelete($id_list);
        }

        $modify = [ENABLE, DISABLE];
        if (in_array($operator, $modify)) {
            return $this->makeUpdateMode($id_list, $operator);
        }

        return $this->back();
    }

    protected function makePostEdit(array $target, int &$id = 0, array &$error = [])
    {
        $flag = $this->makeValidate($target);
        if ($flag) {
            $domain = $this->getDomain('input');

            return $domain->editWithRelation($target, $id, [], $error);
        }

        return false;
    }

    protected function makePostCreate(array $target, int &$id = 0, array &$error = [])
    {
        $flag = $this->makeValidate($target);
        if ($flag) {
            $domain = $this->getDomain('input');

            return $domain->addWithRelation($target, $id, [], $error);
        }

        return false;
    }

    protected function makeDelete(array $target)
    {
        if ($target) {
            $domain = $this->getDomain('delete');

            $flag = $domain->deleteWithRelation($target);
            $this->notify($flag, DELETE);
        }

        return $this->back();
    }

    protected function makeUpdateMode(array $target, int $mode)
    {
        if ($target) {
            $domain = $this->getDomain('modify');
            $flag = $domain->updateMode($target, $mode);
            $this->notify($flag);
        }

        return $this->back();
    }

    protected function getDomain(string $mode)
    {
        switch ($mode) {
            case 'input':
                return Factory::load('domain.menu.root.input');
            case 'read':
                return Factory::load('domain.menu.root.index');
            case 'edit':
                return Factory::load('domain.menu.root.edit');
            case 'delete':
                return Factory::load('domain.menu.root.delete');
            case 'modify':
                return Factory::load('domain.menu.root.input');
        }

        return null;
    }

    protected function form()
    {
        return [
           'master' => Config::read('menu.root.master'),
        ];
    }

    protected function makeValidate(array $target)
    {
        $validate = [
            'menu' => [
                'menu.root.input' => $target
            ]
        ];

        return $this->validate($validate);
    }

    protected function template(string $template = '')
    {
        return 'menu/root/' . $template;
    }
}
