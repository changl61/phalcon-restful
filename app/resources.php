<?php

return [
    // 欢迎
    '/' => [
        'prefix'=> '/',
        'controller' => 'IndexController',
        'actions' => [
            'detail' => '/',
        ],
    ],

    // 令牌
    '_token' => [
        'prefix'=> '/_token',
        'controller' => 'TokenController',
        'actions' => [
            'detail' => '/',
            'delete' => '/',
        ],
    ],

    // 菜品
    'dishes' => [
        'prefix'=> '/dishes',
        'controller' => 'DishController',
        'actions' => [
            'search' => '/',
            'create' => '/',
            'detail' => '/{id:[0-9]+}',
            'update' => '/{id:[0-9]+}',
            'delete' => '/{id:[0-9]+}',
        ],
    ],
];