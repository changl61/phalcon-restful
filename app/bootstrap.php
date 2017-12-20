<?php

// ----------------------------
// 一些基础设置
// ----------------------------
ini_set('date.timezone','Asia/Shanghai');


// ----------------------------
// 加载目录和类
// ----------------------------
$loader = new \Phalcon\Loader();

$loader->registerDirs([
    ROOT_PATH.'/app/controllers',
    ROOT_PATH.'/app/events',
    ROOT_PATH.'/app/exceptions',
    ROOT_PATH.'/app/forms',
    ROOT_PATH.'/app/models',
    ROOT_PATH.'/app/utils',
])->register();

$loader->registerClasses([

]);


// -----------------------------
// 辅助参数
// -----------------------------
if (isset($_REQUEST['_token'])) {
    $_COOKIE['_token'] = $_REQUEST['_token'];
    setcookie('_token', '');
}

if (isset($_REQUEST['_method'])) {
    $_SERVER['REQUEST_METHOD'] = strtoupper($_REQUEST['_method']);
}