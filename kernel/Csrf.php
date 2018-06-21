<?php

namespace Kernel;

class Csrf
{
    protected $active = null;
    protected $capacity = CAPACITY_CSRF;
    protected $collection = [];
    protected $length = 128;
    protected $secret = '';
    protected $storage;

    public function __construct($storage, $length = 128)
    {
        $this->length = $length;
        $this->storage = $storage;
        $this->collection = $this->storage->read('csrf_token', []);
    }

    public function enable($secret)
    {
        $this->secret = sha1(env('USER_AGENT') . time() . $secret);

        return $this;
    }

    public function verify($request)
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $body = $request->getParsedBody();
            $body = $body ? (array)$body : [];

            if (isset($body['_token'])) {
                $token = $body['_token'];
            } else {
                $token = array_first(array_get($request->getHeaders(), 'HTTP_MP_CSRF_TOKEN', []));
            }

            if ($token && $this->validate($token)) {
                $this->release($token);

                return true;
            }

            return false;
        }

        return true;
    }

    public function active()
    {
        if ($this->active) {
            return $this->active;
        }

        return $this->generate();
    }

    public function validate($token)
    {
        return in_array($token, $this->collection);
    }

    public function finalize()
    {
        $this->storage->write('csrf_token', $this->collection);
    }

    public function generate()
    {
        $crsf = md5(sha1($this->secret) . bin2hex(random_bytes($this->length)) . time());

        $this->active = $crsf;
        $this->store($crsf);

        return $crsf;
    }

    protected function store(string $csrf)
    {
        if (count($this->collection) >= $this->capacity) {
            array_shift($this->collection);
        }
        array_push($this->collection, $csrf);
    }

    protected function release(string $csrf)
    {
        $this->collection = array_diff($this->collection, [$csrf]);
    }
}
