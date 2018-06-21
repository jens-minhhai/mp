<?php

namespace App\Handler;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use App\Exception\BadRequest;
use App\Exception\Forbidden;
use App\Exception\Internal;
use App\Exception\NotFound;
use App\Exception\ServiceUnavailable;
use App\Exception\Unauthorized;

class Error extends \Slim\Handlers\Error
{
    public function __construct($displayErrorDetails)
    {
        parent::__construct($displayErrorDetails);
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Exception $exception)
    {
        $code = $exception->getCode();

        switch ($code) {
            case 400: //bad request
            case 401: // unauthorized
            case 403: // forbidden
            case 404: // not found
            case 500: // internal server error
            case 503: // service unavailable
                $body = $this->renderLogicalException($exception, $code);
                break;
            default:
                $code = 500;
                $body = $this->renderFatalException($exception);
        }

        return $response
                   ->withStatus($code)
                   ->withHeader('Content-Type', 'text/html')
                   ->write($body);
    }

    private function render($template = '', array $data = [])
    {
        $app = container('app');
        $channel = array_get($app, 'request.channel_code');

        $loader = new \Twig_Loader_Filesystem(ROOT . '/' . env('APP_VIEW_PATH') . $channel);
        $render = new \Twig_Environment($loader);

        return $render->render($template, $data);
    }

    protected function renderFatalException($exception)
    {
        $title = $exception->getMessage() ?: 'Fatal Error';

        return $this->render('error/fatal.twg', ['title' => $title]);
    }

    protected function renderLogicalException($exception, $template = '404')
    {
        $title = $exception->getMessage() ?: 'Error';
        return $this->render("error/{$template}.twg", ['title' => $title]);
    }
}
