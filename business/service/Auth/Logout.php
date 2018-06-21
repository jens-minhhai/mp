<?php

namespace Service\Auth;

use Session;

class Logout
{
    public function logout()
    {
        Session::destroy();
    }
}
