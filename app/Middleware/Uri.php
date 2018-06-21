<?php

namespace App\Middleware;

use Kernel\Factory;

class Uri
{
    public function __invoke($request, $response, $next)
    {
        $this->boot();

        return $next($request, $response);
    }

    private function boot()
    {
        $app = container('app');

        $request = $app['request'];
        $channel = $request['channel'];

        $app_id = array_get($app, 'app.id', 0);
        $uri = trim(array_get($app, 'request.uri'), '/');

        $target = $this->getByAlias($uri, $app_id, $channel);

        if ($target) {
            $params = $target['params'] ?? [];
            unset($target['params']);

            $request = $this->makeRequest($target['url'], $params, $request);
            $request['req'] = $this->makeOriginRequest($request);
            $app['request'] = $request;
            $app['seo'] = $target;

            container('app', $app);

            return true;
        }

        abort(400);

        return false;
    }

    private function getByAlias(string $url = '', int $app_id = 0, int $channel = CHANNEL_PUBLIC)
    {
        $params = [];
        $match = explode('/', $url);

        foreach ($match as $key => $value) {
            if (strpos($value, ':')) {
                list($name, $value) = explode(':', $value);
                $params[$name] = $value;
                unset($match[$key]);
            }
        }

        $url = implode('/', $match);
        $target = $this->makeGetByAlias($url, $app_id, $channel);

        if ($target) {
            $name = [];
            $url = trim($target['url'], '/');
            $this->makeMapUrl($url, $name);
            $target['params'] = array_merge($params, $name);

            return $target;
        }

        $main = current($match);
        $candidates = $this->makeGetByLikeAlias($main . '_%', $app_id, $channel);

        foreach ($candidates as $target) {
            if ($this->mapUrl($match, $target)) {
                $target['params'] = array_merge($target['params'], $params);

                return $target;
            }
        }

        return [];
    }

    private function mapUrl(array $match = [], array &$target = [])
    {
        $url = $target['alias'];
        $regx = explode('/', trim($url, '/'));

        $params = [];
        preg_match_all('/\/:\w+/', $url, $params);
        $params = current($params);

        if (strpos($url, '#')) {
            list($regx, $match) = $this->wildcard($regx, $match);
        }

        $count_match = count($match);
        $count_regx = count($regx);

        $flower = strpos($url, '*') !== false;
        if (!$flower && $count_match != $count_regx) {
            return false;
        }

        $request_params = [];
        foreach ($match as $key => $item) {
            $value = $regx[$key] ?? '';
            if ($value == '*') {
                break;
            }

            if ($item == $value) {
                $count_regx--;
                continue;
            }

            $against = ltrim(array_shift($params), '/');

            if ($value == $against) {
                $count_regx--;
                $request_params = array_merge($request_params, [trim($value, ':') => $item]);
                continue;
            }

            break;
        }

        if ($count_regx == 0) {
            $target['url'] = $this->makeMapUrl(trim($target['url'], '/'), $request_params);
            $target['params'] = $request_params;

            return true;
        }

        if ($flower) {
            $target['url'] = $this->makeMapUrl(trim($target['url'], '/'), $request_params) .
                             '/' .
                             implode('/', $match);
            $target['params'] = $request_params;

            return true;
        }

        return false;
    }

    private function wildcard(array $regx, array $match = [])
    {
        $tmp = [];
        foreach ($regx as $key => &$pattern) {
            $value = array_shift($match);

            if (strpos($pattern, '#')) {
                $old = $value;
                $modified = false;
                $this->makeWildcard($pattern, $value, $modified);

                if ($modified) {
                    array_unshift($match, $old);
                }
            }
            array_push($tmp, $value);
        }

        return [$regx, $tmp];
    }

    private function makeWildcard(string &$regx, &$input, bool &$modified = false)
    {
        if (strpos($regx, '#') === false) {
            return true;
        }

        $params = explode('#', $regx);
        $regx = array_shift($params);
        $pattern = array_shift($params);

        if (strpos($pattern, '@') === 0) {
            $pattern = substr($pattern, 1);
            $list = explode('@', $pattern);

            foreach ($list as $pattern) {
                $flag = $this->executeFunction($pattern, $input, $modified);
                if ($flag) {
                    return true;
                }
            }

            $input = null;

            return false;
        }

        return $this->executePattern($pattern, $input);
    }

    private function executePattern(string $pattern, &$input, bool &$modified = false)
    {
        $output = [];
        $result = preg_match("/{$pattern}$/", $input, $output);
        if ($result) {
            $input = array_first($output);
        }

        return true;
    }

    private function executeFunction(string $pattern, &$input, bool &$modified = false)
    {
        $params = explode('|', $pattern);
        $func = array_shift($params);

        $negative = (strpos($func, '!') === 0);
        if ($negative) {
            $func = ltrim($func, '!');
        }

        switch ($func) {
            case 'is':
                $check = ($input == array_first($params));
                break;
            case 'null':
                $check = is_null($input);
                break;
            case 'in':
                $array = explode(',', array_shift($params));
                $check = in_array($input, $array);
                break;
            case 'default':
                $input = array_first($params);
                $modified = true;
                $check = true;
                break;
            default:
                $check = true;
        }

        return $negative ? (!$check) : $check;
    }

    private function makeMapUrl(string $url = '', array &$params = [])
    {
        $new_params = [];
        $element = explode('/', $url);

        foreach ($element as $key => $value) {
            $collon = strpos($value, ':');
            $real_value = ($collon === 0 ? substr($value, 1) : $value);

            if (isset($params[$real_value])) {
                $tmp = $params[$real_value];

                $new_params[$real_value] = $tmp;

                $element[$key] = $tmp;
                continue;
            }

            if ($collon) {
                list($name, $value) = explode(':', $value);
                $new_params[$name] = $value;
            }
        }

        $params = array_merge($new_params, $params);
        unset($params['action']);

        return implode('/', $element);
    }

    private function makeGetByAlias(string $url = '', int $app_id = 0, int $channel = CHANNEL_PUBLIC)
    {
        return db()->select('id', 'alias', 'url', 'canonical', 'title', 'keyword', 'description')
                    ->from('seo')
                    ->where('alias', $url)
                    ->where('channel_id', $channel)
                    ->where('app_id', $app_id)
                    ->where('mode', ENABLE)
                    ->first();
    }

    private function makeGetByLikeAlias(string $url = '', int $app_id = 0, int $channel = CHANNEL_PUBLIC)
    {
        return db()->select('id', 'alias', 'url', 'canonical', 'title', 'keyword', 'description')
                    ->from('seo')
                    ->like('alias', $url)
                    ->or(
                        ['raw' => ["INSTR(`alias`, ':') > 0"]],
                        ['raw' => ["INSTR(`alias`, '*') > 0"]]
                    )
                    ->where('channel_id', $channel)
                    ->where('app_id', $app_id)
                    ->where('mode', ENABLE)
                    ->order('priority')
                    ->get();
    }

    private function makeRequest(string $url, array $params = [], array $old = [])
    {
        $request = $this->separateParam($url, $params);
        $request['path'][1] = $request['path'][1] ?? 'index';

        return array_merge($old, $request);
    }

    private function makeOriginRequest($request = [])
    {
        $query = $request['query'];

        $result = [
            'query' => $query
        ];
        $params = $this->separateParam($request['uri']);

        $params['path_string'] = implode('/', $params['path']);

        return array_merge($result, $params);
    }

    private function separateParam(string $url = '', array $name = [])
    {
        $url = trim($url, '/');
        $tmp = explode('/', $url);

        $path = [];
        foreach ($tmp as $item) {
            if ($item) {
                if (mb_strpos($item, ':')) {
                    list($key, $value) = explode(':', $item);
                    if ($key) {
                        $name[$key] = $value ?? '';
                    }
                    continue;
                }
                $path[] = $item;
            }
        }

        return compact('path', 'name');
    }
}
