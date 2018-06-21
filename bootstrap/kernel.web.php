<?php

require ROOT . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();

$class_loader = new Kernel\Dice;

require ROOT. '/bootstrap/web/app.php';
require ROOT. '/bootstrap/web/auth.php';
require ROOT. '/bootstrap/web/common.php';
