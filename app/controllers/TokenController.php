<?php

class TokenController extends ControllerBase
{
    public function detail()
    {
        $form = new FormForToken($this->request->get());
        if (!$form->validate()) throw new ValidationException($form->getErrorMsg(), 400);

        $user = UserModel::attempt($form->mobile, $form->password);
        if (!$user) throw new ValidationException('手机号或密码错误', 400);
        if (!$this->login($user)) throw new ValidationException('服务异常, 请稍后再试', 500);

        return $this->respond([
            '_token' => UserModel::fromSession()['token'],
        ]);
    }

    public function delete()
    {
        if (!$this->logout()) throw new ValidationException('服务异常, 请稍后再试', 500);
        return $this->respond();
    }

    private function login($user)
    {
        $user->agent = $this->request->getUserAgent();
        $user->token = $this->di->get('session')->getId();

        return $user->save() && UserModel::toSession($user);
    }

    private function logout()
    {
        return $this->di->get('session')->destroy();
    }
}