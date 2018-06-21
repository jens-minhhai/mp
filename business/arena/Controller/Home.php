<?php

namespace Arena\Controller;

use Arena\Controller\Base;

class Home extends Base
{
    public function index()
    {
        return $this->render('home/index');
    }
}
