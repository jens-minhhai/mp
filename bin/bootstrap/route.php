<?php

$app
    ->add(new Terminal\Middleware\Console())
    ->add(new Terminal\Middleware\Preload())
    ->add(new Terminal\Middleware\Db())
    ->add(new Terminal\Middleware\Init());
