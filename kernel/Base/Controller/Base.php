<?php

namespace Kernel\Base\Controller;

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

    public function __call(string $name, array $args = [])
    {
        abort('404', 'page not found');
    }
}
