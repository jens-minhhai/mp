<?php

namespace Kernel\Session;

class Native
{
    public $collection = [];

    public function __construct($config = [])
    {
        $this->settings = $config;

        $save_path = $config['save_path'];

        ini_set('session.save_path', $save_path);
        ini_set('session.gc_probability', 1);
        ini_set('session.gc_divisor', 100);
        ini_set('session.gc_maxlifetime', 3600);

        $this->start();
        $this->collection = $_SESSION;
    }

    public function start()
    {
        // session_save_path('/home/example.com/sessions');
        // ini_set('session.gc_probability', 1);
        $settings = $this->settings;
        $name = $settings['name'];

        // session_name($name);
        // session_cache_limiter(false);
        $inactive = session_status() === PHP_SESSION_NONE;

        if ($inactive) {
            // Refresh session cookie when "inactive",
            // else PHP won't know we want this to refresh
            if ($settings['autorefresh'] && isset($_COOKIE[$name])) {
                setcookie(
                    $name,
                    $_COOKIE[$name],
                    time() + $settings['lifetime'],
                    $settings['path'],
                    $settings['domain'],
                    $settings['secure'],
                    $settings['httponly']
                );
            }
        }

        if ($inactive) {
            session_start();
        }
    }

    public function write($key, $value)
    {
        $this->collection = array_insert($this->collection, $key, $value);

        return $this;
    }

    public function check($key)
    {
        return array_exist($this->collection, $key);
    }

    public function read($key, $default = null)
    {
        return array_get($this->collection, $key, $default);
    }

    public function delete($key)
    {
        return array_remove($this->collection, $key);
    }

    public function destroy()
    {
        session_destroy();
        $this->collection = [];
    }

    public function finalize()
    {
        $_SESSION = $this->collection;
    }
}
