<?php

namespace Admin\Controller\Menu;

use Config;
use Factory;
use Request;

use Admin\Controller\Base;

class Index extends Base
{
    public function create(string $root = '')
    {
        $option = [
            'form' => $this->form($root),
            'mode' => 'create',
            'base_url' => $this->baseUrl('menu', "/{$root}")
        ];

        return $this->render($this->template('input'), $option);
    }

    public function postCreate(string $root = '')
    {
        $error = [];
        $id = 0;
        $target = Request::input('menu');

        $flag = $this->makePostCreate($target, $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('menu', "/{$root}/{$id}/edit"));
        }

        if ($error) {
            $this->set('error', $error, true);
        }

        return $this->create($root);
    }

    public function edit(string $root = '', int $id = 0)
    {
        $domain = $this->getDomain('edit');
        $target = $domain->target($id);

        if (empty($target)) {
            abort('404', 'page not found');
        }

        $option = [
            'form' => $this->form($root),
            'mode' => 'edit',
            'target' => $target,
            'base_url' => $this->baseUrl('menu', "/{$root}")
        ];

        return $this->render($this->template('input'), $option);
    }

    public function postEdit(string $root = '', int $id = 0)
    {
        $error = [];
        $target = Request::input('menu');

        $flag = $this->makePostEdit($target, $id, $error);
        $this->notify($flag);
        
        if ($flag) {
            return $this->redirect($this->baseUrl('menu', "/{$root}/{$id}/edit"));
        }

        if ($error) {
            $this->set('error', $error, true);
        }

        return $this->edit($root, $id);
    }

    public function index(string $root = '')
    {
        $domain = $this->getDomain('read');

        $option = [
            'data' => $domain->getTree($root, false),
            'form' => $this->form($root),
            'base_url' => $this->baseUrl('menu', "/{$root}")
        ];

        return $this->render($this->template('index'), $option);
    }

    public function postModify(string $root = '')
    {
        $target = Request::input('menu');

        $id_list = array_get($target, 'id', []);
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

    protected function makePostCreate(array $target = [], int &$id = 0, array &$error = [])
    {
        $flag = $this->makeValidate($target);
        if (!$flag) {
            return false;
        }

        $domain = $this->getDomain('input');

        return $domain->add($target, $id, $error);
    }

    protected function makePostEdit(array $target = [], int &$id = 0, array &$error = [])
    {
        $flag = $this->makeValidate($target);
        if (!$flag) {
            return false;
        }

        $domain = $this->getDomain('input');

        return $domain->edit($target, $id, $error);
    }

    protected function makeDelete($target)
    {
        if ($target) {
            $domain = $this->getDomain('delete');
            $flag = $domain->delete($target);

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

    protected function form(string $root = '')
    {
        $domain = $this->getDomain('read');
        $tree = $domain->getTree($root);
        $tree = array_pluck($tree, '{n}.id', '{n}.display');

        return [
            'tree' => $tree,
            'master' => Config::read('menu.index.master'),
        ];
    }

    protected function getDomain($mode)
    {
        switch ($mode) {
            case 'input':
                return Factory::load('domain.menu.index.input');
            case 'read':
                return Factory::load('domain.menu.index.index');
            case 'edit':
                return Factory::load('domain.menu.index.edit');
            case 'delete':
                return Factory::load('domain.menu.index.delete');
            case 'modify':
                return Factory::load('domain.menu.index.input');
        }

        return null;
    }

    protected function makeValidate($target)
    {
        $validate = [
            'menu' => [
                'menu.index.input' => $target
            ]
        ];

        return $this->validate($validate);
    }

    protected function template(string $template = '')
    {
        return 'menu/index/' . $template;
    }
}
