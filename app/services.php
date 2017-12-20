<?php

use Phalcon\DI\FactoryDefault as DI;
use Phalcon\DI\FactoryDefault\CLI as CliDI;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Session\Adapter\Files as SessionAdapter;

if (APP == 'api') {
    $di = new DI();
} else {
    $di = new CliDI();
}

// 配置项
$di->set('config', function() {
    $config = new ConfigIni(ROOT_PATH.'/app/config/prd.ini');
    if (is_readable(ROOT_PATH.'/app/config/dev.ini')) $config->merge(new ConfigIni(ROOT_PATH.'/app/config/dev.ini'));

    return $config;
});

// 数据库
$di->set('db', function() use ($di) {
    $config = $di->get('config')->get('database')->toArray();

    $dbClass = 'Phalcon\Db\Adapter\Pdo\\' . $config['adapter'];
    unset($config['adapter']);

    return new $dbClass($config);
});

// 会话
$di->set('session', function() use ($di) {
    $session = new SessionAdapter();
    session_name('_token');
    session_set_cookie_params(60*60*12);
    session_save_path(ROOT_PATH.'/cache/session/');
    $session->start();

    return $session;
});

// 返回数据
if (APP == 'api') {
    $di->get('response')->setHeader("Content-Type", "application/json; charset=utf-8");
    $di->get('response')->setHeader("Version", $di->get('config')->get('app')->version);
}

return $di;