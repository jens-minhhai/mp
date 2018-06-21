<?php

define('ROOT', dirname(__DIR__));
require ROOT. '/bootstrap/kernel.web.php';

define('ENV', ROOT . '/env/' . env('APP_ENV'));

load('constant.php', ENV);
$app = new Slim\App(['settings' => load('app.php', ENV)]);

require ENV . '/container.php';
require ROOT . '/bootstrap/route.php';

$app->run();
