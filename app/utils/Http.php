<?php

/**
 * Http 封装(基于curl)
 */
class Http
{
    private $handle;
    private $respond;
    private $error;
    private $startTime;
    private $endTime;

    public function __construct()
    {
        $this->handle = curl_init();
        $this->setOpt(CURLOPT_RETURNTRANSFER, 1); // 不自动输出内容
        $this->setOpt(CURLOPT_HEADER, 0);         // 不返回头部信息
    }

    public function setOpt($key, $value)
    {
        curl_setopt($this->handle, $key, $value);
        return $this;
    }

    public function execute()
    {
        $this->startTime = Utils::getMillisecond();
        $this->respond   = curl_exec($this->handle);
        $this->error     = curl_error($this->handle);
        $this->endTime   = Utils::getMillisecond();
        return $this;
    }

    public function getRespond()
    {
        return $this->respond;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getRuntime()
    {
        return ($this->endTime - $this->startTime) + 'ms';
    }

    public function __destruct()
    {
        curl_close($this->handle);
    }

    public static function request($method = 'GET', $url, $params = [], $encode = true, $headers = [], $timeout = 30)
    {
        $curl = new self();
        $curl->setOpt(CURLOPT_TIMEOUT, $timeout); // 访问超时时间(s)

        // 请求方法
        switch ($method){
            case "GET"   : $curl->setOpt(CURLOPT_HTTPGET, true); break;
            case "POST"  : $curl->setOpt(CURLOPT_POST, true); break;
            case "PUT"   : $curl->setOpt(CURLOPT_CUSTOMREQUEST, 'PUT'); break;
            case "PATCH" : $curl->setOpt(CURLOPT_CUSTOMREQUEST, 'PATCH'); break;
            case "DELETE": $curl->setOpt(CURLOPT_CUSTOMREQUEST, 'DELETE'); break;
        }

        // 地址
        $curl->setOpt(CURLOPT_URL, $url);

        // 参数
        if ($method != 'GET') $curl->setOpt(CURLOPT_POSTFIELDS, $encode ? json_encode($params) : $params);

        // 请求头
        $headers[] = "X-HTTP-Method-Override: {$method}";
        $curl->setOpt(CURLOPT_HTTPHEADER, $headers);

        // 执行
        $curl->execute();

        return [
            'respond'=> $curl->getRespond(),
            'runtime'=> $curl->getRuntime(),
            'error'=>   $curl->getError()
        ];
    }

    public static function get($url, $params = [], $encode = true, $headers = [], $timeout = 30)
    {
        return self::request('GET', $url, $params, $encode, $headers, $timeout);
    }

    public static function post($url, $params = [], $encode = true, $headers = [], $timeout = 30)
    {
        return self::request('POST', $url, $params, $encode, $headers, $timeout);
    }

    public static function put($url, $params = [], $encode = true, $headers = [], $timeout = 30)
    {
        return self::request('PUT', $url, $params, $encode, $headers, $timeout);
    }

    public static function patch($url, $params = [], $encode = true, $headers = [], $timeout = 30)
    {
        return self::request('PATCH', $url, $params, $encode, $headers, $timeout);
    }

    public static function delete($url, $params = [], $encode = true, $headers = [], $timeout = 30)
    {
        return self::request('DELETE', $url, $params, $encode, $headers, $timeout);
    }
}