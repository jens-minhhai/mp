<?php

namespace Arena\Controller;

use App;
use Config;
use Factory;
use Flash;
use Request;
use Utility;

class Base
{
    protected $name = '';
    protected $template_data = [];
    protected $result = [];

    public function validate(array $target = [])
    {
        $errors = [];
        foreach ($target as $name => $data) {
            $error = [];

            $validator = key($data);

            if (App::validate($validator, $data[$validator], $error)) {
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

    public function render($template = '', $data = [])
    {
        self::with($data);

        return $this->result = [
                'type' => 'template',
                'template' => 'page/' . $template . '.twig',
                'data' => $this->template_data
            ];
    }

    public function json($data = [])
    {
        return $this->result = [
                'type' => 'json',
                'data' => $data
            ];
    }

    public function redirect($url, $code = 200)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $url = Utility::url($url);
        }

        $this->result = [
            'redirect' => $url,
            'code' => $code
        ];
    }

    public function back(int $code = 200)
    {
        $url = Request::server('HTTP_REFERER') ?? '';
        $this->redirect($url, $code);
    }

    public function with(array $data = [])
    {
        if ($data) {
            $this->template_data = array_append($this->template_data, $data);
        }
    }

    public function set(string $name, $value, $merge = true)
    {
        if ($merge) {
            $old = array_get($this->template_data, $name, null);
            if (isset($old)) {
                $value = array_merge($old, $value);
            }
        }
        $this->template_data = array_insert($this->template_data, $name, $value);
    }

    protected function notify(bool $flag, int $type = null)
    {
        $message = compact('flag', 'type');

        Flash::write($this->name . '.message', $message);
    }

    public function boot()
    {
    }

    public function result()
    {
        return $this->result;
    }

    public function __call(string $name, array $args = [])
    {
        return $this->boot($name, $args);
    }
}
