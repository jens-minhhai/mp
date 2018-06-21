<?php

namespace Admin\Controller;

use Config;
use Factory;
use Request;

use Admin\Controller\Base;

class Post extends Base
{
    public function create()
    {
        $option = [
            'form' => $this->form(),
            'mode' => 'create',
            'base_url' => $this->baseUrl('post')
        ];

        $template = $this->template('input');

        return $this->render($template, $option);
    }

    public function postCreate()
    {
        $id = 0;
        $error = [];
        $target = Request::input('post');

        $flag = $this->makePostCreate($target, ['seo'], $id, $error);
        $this->notify($flag);

        if ($flag) {
            return $this->redirect($this->baseUrl('post', "/{$id}/edit"));
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
            'base_url' => $this->baseUrl('post')
        ];

        $template = $this->template('input');

        return $this->render($template, $option);
    }

    public function postEdit(int $id = 0)
    {
        $error = [];
        $target = Request::input('post');

        $flag = $this->makePostEdit($target, ['seo'], $id, $error);

        $this->notify($flag);
        if ($flag) {
            return $this->redirect($this->baseUrl('post', "/{$id}/edit"));
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
            'base_url' => $this->baseUrl('post'),
            'form' => $this->form()
        ];

        return $this->render($this->template('index'), $option);
    }

    public function postModify()
    {
        $target = Request::input('post');
        $id_list = $target['id'] ?? [];
        $operator = empty($target['option']) ? 0 : $target['option'];

        if ($operator == DELETE) {
            return $this->makeDelete($id_list);
        }

        $modify = [ENABLE, DISABLE];
        if (in_array($operator, $modify)) {
            return $this->makeUpdateMode($id_list, $operator);
        }

        return $this->back();
    }

    protected function form()
    {
        $instance = Factory::service('category.tree');
        $tree = $instance->nest('post');

        return [
           'master' => Config::read('post.master'),
           'category' => $tree
        ];
    }

    protected function template(string $template = '')
    {
        return 'post/' . $template;
    }

    protected function makePostCreate(array $target, array $association = [], int &$id = 0, array &$error = [])
    {
        $data = array_mask(Request::input(), $association);
        $flag = $this->makeValidate($target, $data);

        if (!$flag) {
            return false;
        }

        $domain = $this->getDomain('input');

        return $domain->addWithRelation($target, $id, $data, $error);
    }

    protected function makePostEdit(array $target, array $association = [], int &$id = 0, array &$error = [])
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
            $domain = $this->getDomain('input');
            $flag = $domain->updateMode($target, $mode);
            $this->notify($flag);
        }

        return $this->back();
    }

    protected function makeValidate(array $target = [], array $association = [])
    {
        $validate = [
            'post' => [
                'post.input' => $target
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
                return Factory::load('domain.post.input');
            case 'edit':
                return Factory::load('domain.post.edit');
            case 'read':
                return Factory::load('domain.post.index');
            case 'delete':
                return Factory::load('domain.post.delete');
        }

        return null;
    }
}
