<?php
namespace Neteast\YS7\Clients\RAM;

use Neteast\YS7\Auth\YS7SubAuth;
use Neteast\YS7\Clients\BaseClient;

/**
 * https://open.ys7.com/doc/zh/book/index/account-api.html
 */
class TokenClient extends BaseClient
{
    /**
     * 获取B模式子账户accessToken
     * https://open.ys7.com/doc/zh/book/index/account-api.html#account-api8
     * @param mixed $accountId
     * @return YS7SubAuth
     */
    public function get($accountId)
    {
        $resp = $this->sendWithAuth(
            '/api/lapp/ram/token/get', [ 'accountId' => $accountId])->json();
        $data = $resp['data'];
        return new YS7SubAuth($this->auth->getAppKey(), $data['accessToken']);
    }
}