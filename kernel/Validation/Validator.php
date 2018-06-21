<?php

namespace Kernel\Validation;

use Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    protected $error = [];

    protected $message = [];

    public function __construct()
    {
        $request = array_get(container('app'), 'request', []);

        $locale_code = $request['locale_code'];
        $channel_code = $request['channel_code'];

        $path = 'config/' .
                $channel_code. '/' .
                $locale_code . '/' .
                'common/validation.php';

        $this->message = load($path);
    }

    public function error()
    {
        return $this->error;
    }

    public function assert(array $data, array $rules)
    {
        $this->error = [];
        extract($this->implement($rules));

        foreach ($rules as $field => $rule) {
            try {
                $rule->assert($data[$field] ?? '');
            } catch (NestedValidationException $exception) {
                $errors = array_filter($exception->findMessages($message[$field]));

                foreach ($errors as $name => $err) {
                    $name = $bridge[$name] ?? $name;

                    $var = $vars[$field][$name];
                    array_unshift($var, $field);

                    $this->error[$field][$name] = $this->formatErrorMessage($err, $var);
                }
            }
        }

        return count($this->error) == 0;
    }

    private function formatErrorMessage($error, $vars)
    {
        return preg_replace_callback(
            '/:\w+/',
            function ($matches) use (&$vars) {
                return array_shift($vars);
            },
            $error
        );
    }

    private function implement(array $default)
    {
        $rules = [];
        $vars = [];
        $message = [];
        $bridge = [];

        foreach ($default as $field => $target) {
            $validator = V::create();

            foreach ($target as $item) {
                $option = $this->getOption($validator, $item);

                extract($option);

                call_user_func_array([$validator, $rule_name], $rule);

                $vars[$field][$alias] = $params;
                $message[$field] = $msg;
                $bridge[$rule_name] = $alias;
            }

            $rules[$field] = $validator;
        }

        return compact('rules', 'message', 'vars', 'bridge');
    }

    private function getOption($validator, string $rule = '')
    {
        $option = explode('|', $rule);

        $alias = $option[0];

        $params = [];
        if (isset($option[1])) {
            $params = explode('@', $option[1]);
        }

        $rule = $this->mapRule($alias, $params);
        $rule_name = array_shift($rule);
        if ($this->isCustomRule($alias)) {
            $validator->with('Kernel\\Validation\\Rules');
        }

        $msg = [
            $rule_name => $option[2] ?? array_get($this->message, $alias, '')
        ];

        return compact('rule_name', 'rule', 'alias', 'params', 'msg');
    }

    private function mapRule(string $name, array $params)
    {
        $rule = [];
        switch ($name) {
            case 'required':
                $rule = ['notBlank'];
                break;
            case 'integer':
                $rule = ['intVal'];
                break;
            case 'min.numeric':
                $rule = ['min'];
                break;
            case 'in_array':
                $rule = ['inArray'];
                break;
            default:
                $rule = [$name];
                break;
        }

        if ($params) {
            return array_merge($rule, $params);
        }

        return $rule;
    }

    private function isCustomRule(string $rule_name)
    {
        $rule_list = [
            'exist',
            'in_array'
        ];

        return in_array($rule_name, $rule_list);
    }
}
