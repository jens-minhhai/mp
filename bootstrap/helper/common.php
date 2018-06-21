<?php

function container(string $name, $target = null)
{
    global $app;

    $container = $app->getContainer();
    if (is_null($target)) {
        return $container[$name] ?? null;
    }

    $container[$name] = $target;
}

function db()
{
    global $app;

    return $app->getContainer()['db'];
}

function exist(string $file, string $root = ROOT)
{
    return file_exists($root . '/' . $file);
}

function env(String $key = null)
{
    $env = getenv($key);
    if ($env === 'true') {
        return true;
    }

    if ($env === 'false') {
        return false;
    }

    return $env;
}

function load(string $file, string $root = ROOT)
{
    return require_once($root . '/' . $file);
}

function make(string $instant, $closure = null)
{
    global $class_loader;

    if ($closure) {
        $closure->call($class_loader);
    }

    return $class_loader->create($instant);
}
