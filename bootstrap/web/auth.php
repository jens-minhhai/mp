<?php

function auth_logined()
{
    return container('session')->check('auth.account');
}

function auth_fallback(string $controller, string $action)
{
    $path = "config.auth.fallback.{$controller}.{$action}";
    return array_get(container('app'), $path, false);
}

function auth_allow(string $controller, string $action)
{
    return auth_fallback($controller, $action) || true;
}

function auth_id()
{
    return container('session')->check('auth.id');
}
