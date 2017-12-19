<?php

use Phalcon\DI;

class AppEvents
{
    public function __construct()
    {
        $this->di = DI::getDefault();
    }

    public function beforeHandleRoute($event, $app)
    {


        return true;
    }

    public function beforeExecuteRoute($event, $app)
    {
        $this->guard();

        return true;
    }

    public function beforeException($event, $app)
    {

        return true;
    }

    public function afterBinding($event, $app)
    {

        return true;
    }

    public function afterExecuteRoute($event, $app)
    {

        return true;
    }

    public function afterHandleRoute($event, $app)
    {

        return true;
    }

    // 门卫
    private function guard()
    {
        $route = AppRouter::getMatchedRoute();
        $user = UserModel::fromSession();
        $accessible = PrivilegeModel::check($route['resource'], $route['action'], $user['role']['id']);

        if (!$accessible) {
            if ($user['role']['name'] == 'guest') {
                throw new AuthException('您还没有登录', 401);
            } else {
                throw new AuthException('没有权限', 403);
            }
        }

        return $this;
    }
}