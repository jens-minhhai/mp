<?php

namespace Kernel\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Stream extends Logger
{
    public function __construct(array $config)
    {
        $name = $config['name'];
        $path = $config['path'];

        parent::__construct($name);

        $path .= '/' . date('Ym/d') . '.log';
        $stream = new StreamHandler($path, Logger::DEBUG);
        $stream->setFormatter(new LineFormatter(null, null, true, true));

        $this->pushHandler($stream);
    }
}
