<?php

namespace Kernel;

class Flash
{
    protected $collection = [];
    protected $name = '';
    protected $storage;

    public function __construct($storage, $name = 'flash')
    {
        $this->storage = $storage;
        $this->name = $name;
        $this->collection = $this->storage->read($this->name, []);
    }

    public function finalize()
    {
        $this->storage->write($this->name, $this->collection);
    }

    public function write(string $path, $value)
    {
        $this->collection = array_insert($this->collection, $path, $value);
    }

    public function check($path)
    {
        return array_exist($this->collection, $path);
    }

    public function read($path, $default = null, $remove = true)
    {
        if ($this->check($path)) {
            $result = array_get($this->collection, $path, $default);
            if ($remove) {
                $this->collection = array_remove($this->collection, $path);
            }

            return $result;
        }

        return $default;
    }
}
