<?php

use Phalcon\Mvc\Model;
use Phalcon\DI;

class UserModel extends Model
{
    // 表的所有字段名
    public $id;
    public $roleId;
    public $nickname;
    public $mobile;
    public $email;
    public $password;
    public $salt;
    public $agent;
    public $token;
    public $loginTime;
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
        return 'user';
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

    // 尝试匹配
    public static function attempt($mobile, $password)
    {
        return self::findFirst([
            'mobile = :mobile: AND password = :password:',
            'bind' => ['mobile' => $mobile, 'password' => $password],
        ]);
    }

    // 读取SESSION缓存
    public static function fromSession()
    {
        $session = DI::getDefault()->getSession();

        return $session->has('user') ? $session->get('user') : [
            'id'   => null,
            'role' => ['id'=>1, 'name'=>'guest', 'zhName'=>'访客'],
            'agent'=>'',
            'token'=>'',
        ];
    }

    // 设置SESSION缓存
    public static function toSession($model)
    {
        $session = DI::getDefault()->getSession();
        $session->set('user', [
            'id'   => $model->id,
            'role' => UserRoleModel::findFirst("id = {$model->roleId}")->toArray(),
            'agent'=> $model->agent,
            'token'=> $model->token,
        ]);

        return true;
    }
}