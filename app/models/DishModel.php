<?php

use Phalcon\Mvc\Model;
use Phalcon\DI;

class DishModel extends Model
{
    // 表的所有字段名
    public $id;
    public $name;
    public $price;
    public $createTime;
    public $updateTime;

    // 初始化
    public function initialize()
    {
        $this->skipAttributesOnCreate(['updateTime']);
        $this->skipAttributesOnUpdate(['createTime']);
    }

    // 数据库表名
    public function getSource()
    {
        return 'dish';
    }

    public function beforeSave()
    {

    }

    public function beforeCreate()
    {
        $this->createTime = date('Y-m-d h:i:s', time());
    }

    public function beforeUpdate()
    {
        $this->updateTime = date('Y-m-d H:i:s', time());
    }

    public function afterFetch()
    {

    }

    /**
     * 查询构造器
     * @param  $request 请求参数
     * @return mixed
     */
    public static function makeFinder($request)
    {
        $conditions = [];
        $bind = [];

        // 搜索名称
        if ($request->get('name')) {
            $conditions[] = "name LIKE :name:";
            $bind['name'] = '%'.$request->get('name').'%';
        }

        return self::find([
            implode($conditions, ' AND '),
            'bind'=> $bind,
            'order'=>'updateTime DESC'
        ]);
    }
}