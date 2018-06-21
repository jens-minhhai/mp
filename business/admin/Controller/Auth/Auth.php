<?php

namespace Admin\Controller\Auth;

use App;
use Factory;
use Request;
use Session;

use Admin\Controller\Base;

class Auth extends Base
{
    public function login()
    {
        if (auth_logined()) {
            return $this->redirect(base_url(), 302);
        }
        $this->render('auth/login/login');
    }

    public function logout()
    {
        $domain = Factory::load('domain.auth.login');
        $domain->logout();

        return $this->redirect('/');
    }

    public function postLogin()
    {
        $auth = Request::input('auth');

        if (!$this->makeValidate($auth)) {
            return $this->login();
        }

        $domain = Factory::load('domain.auth.login');

        $account_id = $domain->login($auth);

        if ($account_id) {
            $user_info = $this->getUserInfo($account_id);

            if ($user_info) {
                Session::write('auth.account', $user_info);

                return $this->redirect('/');
            }
        }

        $this->with([
            'notification' => true
        ]);

        return $this->login();
    }

    private function getUserInfo(int $account_id)
    {
        $service = Factory::global_service('account.auth');

        return $service->target($account_id);
    }

    protected function makeValidate($target)
    {
        $validate = [
            'auth' => [
                'auth.login' => $target
            ],
        ];

        return $this->validate($validate);
    }
}
