<?php

function abort(int $code, string $message = '')
{
    switch ($code) {
        case 400:
            throw new App\Exception\BadRequest($message, $code);
            break;
        case 401:
            throw new App\Exception\Unauthorized($message, $code);
            break;
        case 403:
            throw new App\Exception\Forbidden($message, $code);
            break;
        case 404:
            throw new App\Exception\NotFound($message, $code);
            break;
        case 500:
            throw new App\Exception\Internal($message, $code);
            break;
        case 503:
            throw new App\Exception\ServiceUnavailable($message, $code);
            break;
        default:
            throw new App\Exception\NotFound($message, $code);
            break;
    }
}

function domain()
{
    return array_get(container('app'), 'request.host_full');
}

function base_url()
{
    $req = array_get(container('app'), 'request');
    return $req['host_full'] . '/' . $req['channel_code'];
}
