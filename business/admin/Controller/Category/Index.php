<?php

namespace Admin\Controller\Category;

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
            'base_url' => $this->baseUrl('category', "/{$root}")
        ];

        return $this->render('category/index/input', $option);
    }

    public function postCreate(string $root = '')
    {
        $target = Request::input('category');

        $error = [];
        $id = 0;
        $flag = $this->makePostCreate($target, ['seo'], $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('category', "/{$root}/{$id}/edit"));
        }

        if ($error) {
            $this->set('error', $error, true);
        }

        return $this->create($root);
    }

    public function edit(string $root = '', int $id = 0)
    {
        $domain = $this->getDomain('edit');
        $target = $domain->targetWithRelation($id);

        if (empty($target)) {
            abort('404', 'page not found');
        }

        $option = [
            'form' => $this->form($root),
            'mode' => 'edit',
            'target' => $target,
            'base_url' => $this->baseUrl('category', "/{$root}")
        ];

        return $this->render('category/index/input', $option);
    }

    public function postEdit(string $root = '', int $id = 0)
    {
        $target = Request::input('category');
        $error = [];
        $flag = $this->makePostEdit($target, ['seo'], $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('category', "/{$root}/{$id}/edit"));
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
            'form' => $this->form($root, false),
            'base_url' => $this->baseUrl('category', "/{$root}")
        ];
// print_r("<pre>");
// print_r($option);
// print_r("</pre>");
// exit;
        return $this->render('category/index/index', $option);
    }

    public function postModify(string $root = '')
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

    protected function form(string $root = '', bool $get_tree = true)
    {
        if ($get_tree) {
            $domain = $this->getDomain('read');
            $tree = $domain->getTree($root);
            $tree = array_pluck($tree, '{n}.id', '{n}.display');

            return [
                'tree' => $tree,
                'master' => Config::read('category.index.master')
            ];
        }
        return [
            'master' => Config::read('category.index.master')
        ];
    }

    protected function makePostCreate(array $target = [], array $association = [], int &$id = 0, array &$error = [])
    {
        $data = array_mask(Request::input(), $association);
        $flag = $this->makeValidate($target, $data);
        if (!$flag) {
            return false;
        }

        $domain = $this->getDomain('input');

        return $domain->addWithRelation($target, $id, $data, $error);
    }

    protected function makePostEdit(array $target = [], array $association = [], int $id = 0, array &$error = [])
    {
        $data = array_mask(Request::input(), $association);
        $flag = $this->makeValidate($target, $data);
        if (!$flag) {
            return false;
        }

        $domain = $this->getDomain('input');

        return $domain->editWithRelation($target, $id, $data, $error);
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

    protected function makeValidate(array $target = [], array $association = [])
    {
        $validate = [
            'category' => [
                'category.index.input' => $target
            ],
            'seo' => [
                'seo.input' => $association['seo']
            ]
        ];

        return $this->validate($validate);
    }

    protected function getDomain($mode)
    {
        switch ($mode) {
            case 'input':
                return Factory::load('domain.category.index.input');
            case 'read':
                return Factory::load('domain.category.index.index');
            case 'edit':
                return Factory::load('domain.category.index.edit');
            case 'delete':
                return Factory::load('domain.category.index.delete');
            case 'modify':
                return Factory::load('domain.category.index.input');
        }

        return null;
    }
}
