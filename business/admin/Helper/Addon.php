<?php

namespace Admin\Helper;

use Factory;

class Addon
{
    public function data()
    {
        $sidebar = $this->getSidebar();
        return ['addon' => compact('sidebar')];
    }

    private function getSidebar()
    {
        $root = 'sidebar-left';
        $domain = Factory::global_service('menu.index');
        return $domain->getTree($root, false);
    }
}
