<?php

/**
 * 调试工具
 */
class Debug
{
    public static function printR($val)
    {
        header("Content-type:text/html; charset=utf-8");
        echo '<pre>';
        print_r($val);
        exit;
    }

    /**
     * 打印类的属性和方法
     * @param $class object
     */
    public static function printClass($class)
    {
        echo '<pre>';
        print_r(get_class_vars(get_class($class)));
        print_r(get_class_methods($class));
        exit;
    }
}