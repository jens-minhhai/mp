<?php

function app_api()
{
    return array_get(container('app'), 'app.api');
}

function app_code()
{
    return array_get(container('app'), 'app.code');
}

function app_id()
{
    return array_get(container('app'), 'app.id');
}

function app_title()
{
    return array_get(container('app'), 'app.title');
}

function app_token()
{
    return array_get(container('app'), 'app.token');
}
