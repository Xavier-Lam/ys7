<?php
namespace Neteast\YS7\Clients;

use Neteast\YS7\YS7Auth;

/**
 * 令牌
 * https://open.ys7.com/doc/zh/book/index/user.html
 */
class TokenClient extends BaseClient
{
    public function get(YS7Auth $auth)
    {
        $resp = $this->send('/api/lapp/token/get', 'POST', [
            'appKey' => $auth->getAppKey(),
            'appSecret' => $auth->getAppSecret()
        ]);
        $data = $resp->json();
        $auth->setAccessToken($data['data']['accessToken'], $data['data']['expireTime']);
        return $auth;
    }
}