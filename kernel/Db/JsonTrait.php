<?php

namespace Kernel\Db;

trait JsonTrait
{
    public function json_where($column, $path, $value, string $operator = '=', string $escape = '')
    {
        $regex = $this->json_extract($column, $path);

        return $this->regexColumn($regex, $value, $operator, $escape);
    }

    public function json_array($target = [])
    {
        $query = 'JSON_ARRAY(';
        foreach ($target as $key => $value) {
            $query .= "'{$value}',";
        }

        return trim($query, ',') . ')';
    }

    public function json_object($target = [])
    {
        $query = 'JSON_OBJECT(';
        foreach ($target as $key => $value) {
            $query .= "'{$key}', '{$value}',";
        }

        return trim($query, ',') . ')';
    }

    public function json_exist($column, $target = [], $one = true)
    {
        $type = $one ? 'one' : 'all';
        $query = "JSON_CONTAINS_PATH({$column}, {$type}";
        foreach ($target as $path) {
            $query .= "'{$path}'";
        }

        return trim($query, ',') . ')';
    }

    public function json_array_func(string $column, array $target = [], string $func = 'set')
    {
        switch ($func) {
            case 'insert':
                $func = 'JSON_ARRAY_INSERT';
                break;
            default:
                $func = 'JSON_ARRAY_APPEND';
                break;
        }

        $query = "{$func}({$column},";
        foreach ($target as $path => $value) {
            $query .= $this->quote($value) . ',';
        }

        return trim($query, ',') . ')';
    }

    public function json_func(string $column, array $target = [], string $func = 'set')
    {
        switch ($func) {
            case 'insert':
                $func = 'JSON_INSERT';
                break;
            case 'replace':
                $func = 'JSON_REPLACE';
                break;
            default:
                $func = 'JSON_SET';
                break;
        }

        $query = "{$func}({$column},";
        foreach ($target as $path => $value) {
            $query .= "'{$path}', " . $this->quote($value) . ',';
        }

        return trim($query, ',') . ')';
    }

    public function json_remove(string $column, string ...$target)
    {
        $query = "JSON_REMOVE({$column},";
        foreach ($target as $path) {
            if ($path[0] != '$') {
                $path = "$.{$path}";
            }
            $query .= "'{$path}',";
        }

        return trim($query, ',') . ')';
    }

    public function json_extract($column, string $path = '')
    {
        return "JSON_EXTRACT({$column}, '{$path}')";
    }

    public function json_contain($column, $value, string $path = '')
    {
        if ($path) {
            return "JSON_CONTAINS({$column}, '" . $this->quote($value) . "', '{$path}')";
        }

        return "JSON_CONTAINS({$column}, '" . $this->quote($value) . "')";
    }

    private function quote($value)
    {
        if (is_string($value)) {
            $value = "JSON_QUOTE('{$value}')";
        }

        return $value;
    }
}
