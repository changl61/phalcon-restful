<?php

class FormForDish extends FormBase
{
    // 字段名称映射
    protected $_mapping = [
        'name'  => '名称',
        'price' => '价格',

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

        'create' => [
            'name'  => 'required|uniqueName:`系统已存在`',
            'price' => 'required|range(0,100)',
        ],

        'update' => [
            'id' => 'int',
            'name'  => 'required|uniqueName:`系统已存在`',
            'price' => 'required|range(0,100)',
        ],
    ];


    // -----------------------------
    //  自定义验证方法
    // -----------------------------
    protected function check_uniqueName($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        $same = DishModel::findFirst([
            'name = :name: AND id != :id:',
            'bind' => [
                'name' => $this->$attr,
                'id' => property_exists($this, 'id') ? $this->id : 0,
            ]
        ]);

        if ($same) return $message;

        return true;
    }
}