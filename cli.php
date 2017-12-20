<?php

use Phalcon\CLI\Console as ConsoleApp;

try {
    // --------------------------
    // 定义目录
    // --------------------------
    define('ROOT_PATH', realpath('.'));
    define('APP', 'cli');


    // --------------------------
    // 启动引导
    // --------------------------
    require_once (ROOT_PATH.'/app/bootstrap.php');


    // --------------------------
    // 命令行实例
    // --------------------------
    $app = new ConsoleApp();

    // 服务容器
    $app->setDI(require_once (ROOT_PATH.'/app/services.php'));

    // 路由规则
    $arguments = array();
    foreach($argv as $k => $arg) {
        if($k == 1) {
            $arguments['task'] = $arg;
        } elseif($k == 2) {
            $arguments['action'] = $arg;
        } elseif($k >= 3) {
            $arguments['params'][] = $arg;
        }
    }

    $app->handle($arguments);
}

catch (Exception $e) {
    echo $e->getMessage().' '.$e->getCode();
}

echo "\n";