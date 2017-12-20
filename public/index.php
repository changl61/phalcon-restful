<?php

use Phalcon\Mvc\Micro;
use Phalcon\Events\Manager as EventsManager;

try {
    // --------------------------
    // 定义目录
    // --------------------------
    define('ROOT_PATH', realpath('..'));
    define('APP', 'api');

    // --------------------------
    // 启动引导
    // --------------------------
    require_once (ROOT_PATH.'/app/bootstrap.php');


    // --------------------------
    // 微应用实例
    // --------------------------
    $app = new Micro();

    // 服务容器
    $app->setDI(require_once (ROOT_PATH.'/app/services.php'));

    // 路由规则
    AppRouter::setRoutes($app, require_once (ROOT_PATH.'/app/resources.php'));
    $app->notFound(function () {throw  new HttpException('The resource was not found!', 404);});

    // 监听事件
    $eventManager = new EventsManager();
    $eventManager->attach('micro', new AppEvents());
    $app->setEventsManager($eventManager);

    $app->handle();
}

catch (Exception $e) {
    echo json_encode([
        'msg' => $e->getMessage(),
        'data' => null,
        'status' => $e->getCode(),
    ]);
}