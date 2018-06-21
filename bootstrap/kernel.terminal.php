<?php

require ROOT . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(BIN);
$dotenv->load();

$class_loader = new Kernel\Dice;
