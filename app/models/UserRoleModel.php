<?php

use Phalcon\Mvc\Model;
use Phalcon\DI;

class UserRoleModel extends Model
{
    // 表的所有字段名
    public $id;
    public $name;
    public $zhName;
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
        return 'user_role';
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
}