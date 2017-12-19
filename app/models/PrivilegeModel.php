<?php

use Phalcon\Mvc\Model;
use Phalcon\DI;

class PrivilegeModel extends Model
{
    // 表的所有字段名
    public $id;
    public $resource;
    public $userRoleId;
    public $fields = '[]';
    public $detail = '0';
    public $create = '0';
    public $update = '0';
    public $delete = '0';
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
        return 'privilege';
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

    public static function check($resource, $method, $userRoleId)
    {
        $model = self::findFirst("resource = '{$resource}' AND userRoleId = {$userRoleId}");
        return ($model && $model->$method);
    }
}