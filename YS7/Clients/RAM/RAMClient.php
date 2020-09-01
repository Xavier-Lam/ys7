<?php
namespace Neteast\YS7\Clients\RAM;

use Neteast\YS7\Client\AuthClient;

/**
 * 子账户
 * https://open.ys7.com/doc/zh/book/index/account-api.html
 * 
 * @property AccountClient $account 账号
 * @property PolicyClient $policy 策略
 * @property TokenClient $token 令牌
 */
class RAMClient extends AuthClient
{
    protected $clients = [
        'account' => AccountClient::class,
        'policy' => PolicyClient::class,
        'token' => TokenClient::class
    ];
}