<?php

namespace Admin\Controller\Category;

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
            'base_url' => $this->baseUrl('category', '/root')
        ];

        return $this->render($this->template('input'), $option);
    }

    public function postCreate()
    {
        $target = Request::input('category');
        $error = [];
        $id = 0;

        $flag = $this->makePostCreate($target, $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('category', "/root/{$id}/edit"));
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
            'base_url' => $this->baseUrl('category', '/root')
        ];

        return $this->render($this->template('input'), $option);
    }

    public function postEdit(int $id = 0)
    {
        $target = Request::input('category');

        $error = [];
        $flag = $this->makePostEdit($target, $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('category', "/root/{$id}/edit"));
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
            'base_url' => $this->baseUrl('category')
        ];

        return $this->render($this->template('index'), $option);
    }

    public function postModify()
    {
        $target = Request::input('category');

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

    protected function makePostCreate(array $target, int &$id = 0, &$error = [])
    {
        $flag = $this->makeValidate($target);
        if ($flag) {
            $domain = $this->getDomain('input');

            return $domain->addWithRelation($target, $id, [], $error);
        }

        return false;
    }

    protected function makePostEdit(array $target, int &$id = 0, &$error = [])
    {
        $flag = $this->makeValidate($target);
        if ($flag) {
            $domain = $this->getDomain('input');

            return $domain->editWithRelation($target, $id, [], $error);
        }

        return false;
    }

    protected function makeDelete($target)
    {
        if ($target) {
            $domain = $this->getDomain('delete');

            $flag = $domain->deleteWithRelation($target);
            $this->notify($flag, DELETE);
        }

        return $this->back();
    }

    protected function makeUpdateMode($target, $mode)
    {
        if ($target) {
            $domain = $this->getDomain('modify');
            $flag = $domain->updateMode($target, $mode);
            $this->notify($flag);
        }

        return $this->back();
    }

    protected function form()
    {
        return [
           'master' => Config::read('category.root.master')
        ];
    }

    protected function makeValidate($target)
    {
        $validate = [
                'category' => [
                    'category.root.input' => $target
                ]
            ];

        return $this->validate($validate);
    }

    protected function getDomain($mode)
    {
        switch ($mode) {
            case 'input':
                return Factory::load('domain.category.root.input');
            case 'read':
                return Factory::load('domain.category.root.index');
            case 'edit':
                return Factory::load('domain.category.root.edit');
            case 'delete':
                return Factory::load('domain.category.root.delete');
            case 'modify':
                return Factory::load('domain.category.root.input');
        }

        return null;
    }

    protected function template(string $template = '')
    {
        return 'category/root/' . $template;
    }
}
