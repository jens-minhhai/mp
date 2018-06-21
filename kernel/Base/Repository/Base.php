<?php

namespace Kernel\Base\Repository;

class Base
{
    use \Kernel\Traits\Repository\MasterTrait;

    protected $pk = 'id';
    protected $table = '';

    public function with($rule, ... $args)
    {
        call_user_func_array([db(), $rule], $args);
        return $this;
    }

    public function begin()
    {
        return db()->begin();
    }

    public function commit()
    {
        return db()->commit();
    }

    public function rollback()
    {
        return db()->rollback();
    }

    public function lastInsertedId()
    {
        return db()->lastInsertedId();
    }

    public function __call(string $func, array $args = [])
    {
        return $this->apply($func, $args);
    }

    protected function db()
    {
        return db()->from($this->table);
    }

    protected function apply(string $func, array $args = [])
    {
        $db = $this->db();

        $scopes = $this->scope();
        if ($scopes) {
            foreach ($scopes as $scope) {
                $rule = array_shift($scope);
                call_user_func_array([$db, $rule], $scope);
            }
        }
        return call_user_func_array([$db, $func], $args);
    }

    protected function scope()
    {
        return [
            'alive' => ['alive'],
            'app_id' => ['where', 'app_id', app_id()]
        ];
    }

    public function offset(string $name = '')
    {
        return $this->$name;
    }
}
