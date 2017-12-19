<?php

use Phalcon\DI;
use Phalcon\Mvc\Micro\Collection as MicroCollection;

/**
 * 路由
 */
class AppRouter
{
    private static $routes = [];

    /**
     * 获取当前匹配到的路由
     *
     * return string  如:'GET /'
     */
    public static function getMatchedRoute()
    {
        $route = DI::getDefault()->get('router')->getMatchedRoute();
        if (!$route) return [];

        $key = strtolower($route->getHttpMethods()).' '.$route->getPattern();
        if (!isset(self::$routes[$key])) throw new HttpException('Server error!', 500);

        return self::$routes[$key];
    }

    /**
     * 设置路由
     * @param $app       object
     * @param $resources array
     */
    public static function setRoutes($app, $resources)
    {
        if (count(self::$routes)) return; // 只能设置一次

        foreach ($resources as $resourceName => $resource) {

            $routeCollection = new MicroCollection();
            $routeCollection->setHandler($resource['controller'], true);
            $routeCollection->setPrefix($resource['prefix']);

            // 列表
            if (isset($resource['actions']['search'])) {
                $routeCollection->get($resource['actions']['search'], 'search');

                self::$routes['get '.$resource['prefix'].rtrim($resource['actions']['search'], '/')] = [
                    'resource'   => $resourceName,
                    'method'     => 'get',
                    'controller' => $resource['controller'],
                    'action'     => 'search',
                ];
            }

            // 新增
            if (isset($resource['actions']['create'])) {
                $routeCollection->post($resource['actions']['create'], 'create');

                self::$routes['post '.$resource['prefix'].rtrim($resource['actions']['create'], '/')] = [
                    'resource'   => $resourceName,
                    'method'     => 'post',
                    'controller' => $resource['controller'],
                    'action'     => 'create',
                ];
            }

            // 详情
            if (isset($resource['actions']['detail'])) {
                $routeCollection->get($resource['actions']['detail'], 'detail');

                self::$routes['get '.$resource['prefix'].rtrim($resource['actions']['detail'], '/')] = [
                    'resource'   => $resourceName,
                    'method'     => 'get',
                    'controller' => $resource['controller'],
                    'action'     => 'detail',
                ];
            }

            // 更新
            if (isset($resource['actions']['update'])) {
                $routeCollection->patch($resource['actions']['update'], 'update');

                self::$routes['patch '.$resource['prefix'].rtrim($resource['actions']['update'], '/')] = [
                    'resource'   => $resourceName,
                    'method'     => 'patch',
                    'controller' => $resource['controller'],
                    'action'     => 'update',
                ];
            }

            // 删除
            if (isset($resource['actions']['delete'])) {
                $routeCollection->delete($resource['actions']['delete'], 'delete');

                self::$routes['delete '.$resource['prefix'].rtrim($resource['actions']['delete'], '/')] = [
                    'resource'   => $resourceName,
                    'method'     => 'delete',
                    'controller' => $resource['controller'],
                    'action'     => 'delete',
                ];
            }

            // 注册路由集合
            $app->mount($routeCollection);
        }
    }
}