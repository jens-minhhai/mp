<?php

namespace Admin\Controller;

use App;
use Config;
use Factory;
use Flash;
use Request;
use Utility;

class Base
{
    protected $template_data = [];
    protected $result = [];

    public function result()
    {
        return $this->result;
    }

    protected function validate(array $target = [])
    {
        $errors = [];
        foreach ($target as $name => $data) {
            $error = [];

            $validator = key($data);

            $rule = Config::load('validation.' . $validator);
            if ($rule) {
                $rule = $this->modifiedRule($rule, $data[$validator]);
            }

            if (App::validate($data[$validator], $rule, $error)) {
                continue;
            }

            $errors[$name] = $error;
        }

        if ($errors) {
            $this->with(['error' => $errors]);

            return false;
        }

        return true;
    }

    protected function modifiedRule(array $rule, array $target)
    {
        return $rule;
    }

    protected function render(string $template = '', array $data = [], bool $addon_flag = true)
    {
        self::with($data);

        if ($addon_flag) {
            $addon = Factory::helper('addon')->data();
            if ($addon) {
                self::with($addon);
            }
        }

        return $this->result = [
                'type' => 'template',
                'template' => 'page/' . $template . '.twig',
                'data' => $this->template_data
            ];
    }

    protected function json(array $data = [])
    {
        return $this->result = [
                'type' => 'json',
                'data' => $data
            ];
    }

    protected function redirect(string $url, int $code = 200)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $url = Utility::url($url);
        }

        $this->result = [
            'redirect' => $url,
            'code' => $code
        ];
    }

    protected function back(int $code = 200)
    {
        $url = Request::server('HTTP_REFERER') ?? '';
        $this->redirect($url, $code);
    }

    protected function with(array $data = [])
    {
        if ($data) {
            $this->template_data = array_append($this->template_data, $data);
        }
    }

    protected function set(string $name, $value, bool $merge = true)
    {
        if ($merge) {
            $old = array_get($this->template_data, $name, null);
            if (isset($old)) {
                $value = array_merge($old, $value);
            }
        }
        $this->template_data = array_insert($this->template_data, $name, $value);
    }

    protected function baseUrl(string $base_url, string $url = '')
    {
        $params = Request::param();
        $channel = $params['channel'] ?? '';
        $locale = $params['locale'] ?? '';

        if ($channel) {
            if ($channel != Config::anonymous('channel')) {
                $base_url .= "/{$channel}";
            }
        }

        if ($locale) {
            if ($locale != Config::anonymous('locale')) {
                $base_url .= "/{$locale}";
            }
        }

        return $base_url . $url;
    }

    protected function notify(bool $flag, int $mode = null)
    {
        Flash::write('message', compact('flag', 'mode'));
    }

    public function __call(string $name, array $args = [])
    {
        abort('404', 'page not found');
    }
}
