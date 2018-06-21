<?php

namespace Kernel\Base\Domain;

class Base
{
    protected $attribute = [];
    protected $repository = null;

    public function __construct($repository = null)
    {
        $this->repository = $repository;
    }

    public function repository()
    {
        return $this->repository;
    }

    public function transaction(...$closures)
    {
        db()->begin();
        foreach ($closures as $closure) {
            $flag = $closure->call($this);
            if ($flag) {
                continue;
            }
            db()->rollback();

            return false;
        }
        
        db()->commit();

        return true;
    }

    public function offset($name)
    {
        return $this->$name;
    }

    public function __call(string $func, array $args = [])
    {
        return call_user_func_array([$this->repository, $func], $args);
    }
}
