<?php

use Phalcon\DI;

class FormBase
{
    private $_fields = [];
    private $_rules = [];
    private $_errors  = [];

    protected $_mapping = [];
    protected $_scenes = ['default' => []];

    // -----------------------------
    //  表单相关
    // -----------------------------
    public function __construct($data, $sceneName = 'default')
    {
        $scene = $this->getScene($sceneName);
        $this->setFields($scene);
        $this->setRules($scene);
        $this->setAttrs($data);
    }

    public function validate()
    {
        foreach ($this->_rules as $field=>$rules) {
            foreach ($rules as $rule) {
                $result = $this->check($field, $rule);

                if ($result !== true) {
                    $this->setError($field, $result, $rule['param']);
                    break;
                }
            }
        }

        return !count($this->_errors);
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function getErrorMsg()
    {
        $errors = [];

        foreach ($this->_errors as $field=>$error) {
            $filedName = isset($this->_mapping[$field]) ? $this->_mapping[$field] : $field;
            $errors[] = '['.$filedName.']'.$error;
        }

        return implode(';', $errors);
    }

    public function assignTo($obj)
    {
        foreach ($this->_fields as $field) {
            if (property_exists($obj, $field)) $obj->$field = $this->$field;
        }

        return true;
    }

    private function getScene($name)
    {
        if (!isset($this->_scenes[$name]) || !is_array($this->_scenes[$name])) throw new HttpException('Server Error!', 500);

        return $this->_scenes[$name];
    }

    private function setFields($scene)
    {
        $this->_fields = array_keys($scene);

        return $this;
    }

    private function setRules($scene)
    {
        foreach ($scene as $key=>$str) {
            $this->_rules[$key] = $this->parseRules($str);
        }

        return $this;
    }

    private function parseRules($str)
    {
        $rules = [];
        $arr = explode('|', $str);

        foreach ($arr as $item) {
            if (!$item) continue;

            $rule = [];

            // 消息
            preg_match('/\:\`.*\`/', $item, $matches);
            if (count($matches)) {
                $item = str_replace($matches[0], '', $item);
                $rule['message'] = trim($matches[0], ':`');
            }

            // 参数
            preg_match('/\(.*\)/', $item, $matches);
            if (count($matches)) {
                $item = str_replace($matches[0], '', $item);
                $rule['param'] = explode(',', trim($matches[0], '()'));
            }


            // 方法
            $rule['method'] = $item;

            if (!isset($rule['message'])) $rule['message'] = isset(self::$_methods[$item]) ? self::$_methods[$item] : '';
            if (!isset($rule['param'])) $rule['param'] = [];
            $rules[] = $rule;
        }

        return $rules;
    }

    private function setAttrs($data)
    {
        foreach ($this->_fields as $field)
        {
            $this->$field = isset($data[$field]) ? $data[$field] : null;
        }

        return $this;
    }

    private function setError($field, $message, $param)
    {
        foreach ($param as $index=>$value) {
            $message = str_replace('{'.$index.'}', $value, $message);
        }

        $this->_errors[$field] = $message;
        return $this;
    }

    // -----------------------------
    //  系统验证方法
    // -----------------------------
    protected static $_methods = [
        // 必填
        'required' => '是必填项',
        // 类型
        'bool' => '须是布尔类型',
        'int' => '须是整型',
        'number' => '须是数字类型',
        'json' => '须是JSON格式',
        // 比较
        'length' => '须是{0}至{1}位字符',
        'minLength' => '至少{0}位字符',
        'maxLength' => '至多{0}位字符',
        'range' => '须介于{0}到{1}之间',
        'min' => '至少为{0}',
        'max' => '至多为{0}',
        // 匹配
        'match' => '须符合要求的格式',
        'date' => '须是日期格式',
        'email' => '须是电子邮件地址',
        'mobile' => '须是正确的11位手机号码',
        // 安全
        'safe' => '', // 不会报出错误信息, 仅做一些sql危险字符替换或过滤。
    ];
    protected function check($attr, $rule)
    {
        $check_method = 'check_'.$rule['method'];
        if (method_exists($this, $check_method)) {
            return $this->$check_method($attr, $rule['param'], $rule['message']);
        }

        return true;
    }

    protected function check_safe($attr, $param, $message)
    {


        return true;
    }

    protected function check_required($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return $message;

        return true;
    }

    protected function check_bool($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        if ($this->$attr !== true && $this->$attr === false) return $message;

        return true;
    }

    protected function check_int($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        if (!self::isInt($this->$attr)) return $message;

        $this->$attr = (int)$this->$attr;

        return true;
    }

    protected function check_number($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        if (!self::isNumber($this->$attr)) return $message;

        return true;
    }

    protected function check_json($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        if (!is_array(json_decode($this->$attr, true))) return $message;

        if (trim($param[0] == 'decode')) $this->$attr = json_decode($this->$attr, true);

        return true;
    }

    protected function check_length($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        $this->$attr = ''.$this->$attr;
        $length = mb_strlen($this->$attr, 'utf-8');
        $param[0] = (int)trim($param[0]);
        $param[1] = (int)trim($param[1]);

        if ($length < $param[0] || $length > $param[1]) return $message;

        return true;
    }

    protected function check_minLength($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        $this->$attr = ''.$this->$attr;
        $length = mb_strlen($this->$attr, 'utf-8');
        $param[0] = (int)trim($param[0]);

        if ($length < $param[0]) return $message;

        return true;
    }

    protected function check_maxLength($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        $this->$attr = ''.$this->$attr;
        $length = mb_strlen($this->$attr, 'utf-8');
        $param[0] = (int)trim($param[0]);

        if ($length > $param[0]) return $message;

        return true;
    }

    protected function check_range($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;
        if (!self::isNumber($this->$attr)) return '须是数字类型';

        $param[0] = (int)trim($param[0]);
        $param[1] = (int)trim($param[1]);

        if ($this->$attr < $param[0] || $this->$attr > $param[1]) return $message;

        return true;
    }

    protected function check_min($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;
        if (!self::isNumber($this->$attr)) return '须是数字类型';

        $param[0] = (int)trim($param[0]);

        if ($this->$attr < $param[0]) return $message;

        return true;
    }

    protected function check_max($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;
        if (!self::isNumber($this->$attr)) return '须是数字类型';

        $param[0] = (int)trim($param[0]);

        if ($this->$attr > $param[0]) return $message;

        return true;
    }

    protected function check_match($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        $param[0] = trim($param[0], " '");

        if (!preg_match($param[0], $this->$attr)) return $message;

        return true;
    }

    protected function check_date($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;

        if (!preg_match('/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/', $this->$attr)) return $message;

        return true;
    }

    protected function check_email($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;
        if (!self::isEmail($this->$attr)) return $message;

        return true;
    }

    protected function check_mobile($attr, $param, $message)
    {
        if (!self::isFilled($this->$attr)) return true;
        if (!self::isMobile($this->$attr)) return $message;

        return true;
    }

    // -----------------------------
    //  静态方法
    // -----------------------------
    public static function isFilled($val)
    {
        return $val !== null && $val !== '';
    }

    public static function isInt($str)
    {
        return (bool) preg_match('/^\-?\d+$/', $str);
    }

    public static function isNumber($str)
    {
        return (bool) preg_match('/^\-?\d+(\.\d+)?$/', $str);
    }

    public static function isEmail($str)
    {
        return (bool) preg_match('/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/', $str);
    }

    public static function isMobile($str)
    {
        return (bool) preg_match('/^1[34578][0-9]{9}$/', $str);
    }
}