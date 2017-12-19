<?php

use Phalcon\DI;

class ControllerBase
{
    protected $di;
    protected $request;
    protected $response;

    public function __construct()
    {
        $this->di = DI::getDefault();
        $this->request = $this->di->get('request');
        $this->response = $this->di->get('response');
    }

    protected function respond($data = null)
    {
        $json = json_encode([
            'msg' => 'ok',
            'data' => $data,
            'status' => 200
        ]);

        return $this->response->setContent($json);
    }
}