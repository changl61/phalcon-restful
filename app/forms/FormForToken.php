<?php

class FormForToken extends FormBase
{
    // 字段名称映射
    protected $_mapping = [
        'mobile'   => '手机号',
        'password' => '密码',

    ];

    // 验证场景
    protected $_scenes = [
        /**
         * 验证规则格式如下:
         * $method[$param0,$param1,...][:`$message`]|$method2
         *
         * 错误消息($message)部分:
         * ×××{0}×××{1}×××  {n}对应前面的参数
         */

        // 默认场景
        'default' => [
            'mobile'   => 'required|mobile',
            'password' => 'required|minLength(6)',
        ],
    ];


    // -----------------------------
    //  自定义验证方法
    // -----------------------------
    protected function check_someMethod($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        // ...

        return true;
    }
}