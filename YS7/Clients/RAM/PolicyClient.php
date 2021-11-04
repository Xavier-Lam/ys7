<?php

namespace Neteast\YS7\Clients\RAM;

use Neteast\YS7\Clients\BaseClient;
use Neteast\Policy\Statement;

/**
 * 权限策略
 * https://open.ys7.com/doc/zh/book/index/account-api.html
 */
class PolicyClient extends BaseClient
{
    /**
     * 设置子账户的授权策略
     * https://open.ys7.com/doc/zh/book/index/account-api.html#account-api5
     * @param mixed $accountId
     * @param Statement[] $policy
     */
    public function set($accountId, $policy)
    {
        $this->sendWithAuth(
            '/api/lapp/ram/policy/set',
            [
                'accountId' => $accountId,
                'policy' => json_encode([
                    "Statement" => map(function ($o) {
                        return $o->data();
                    }, $policy)
                ])
            ]
        );
    }

    /**
     * 增加子账户权限
     * https://open.ys7.com/doc/zh/book/index/account-api.html#account-api6
     * @param mixed $accountId
     * @param Statement[] $policy
     */
    public function add($accountId, $policy)
    {
        $this->sendWithAuth(
            '/api/lapp/ram/policy/add',
            [
                'accountId' => $accountId,
                'policy' => json_encode([
                    "Statement" => map(function ($o) {
                        return $o->data();
                    }, $policy)
                ])
            ]
        );
    }

    /**
     * 删除子账户权限
     * https://open.ys7.com/doc/zh/book/index/account-api.html#account-api7
     * @param mixed $accountId
     * @param $deviceSerial
     */
    public function delete($accountId, $deviceSerial)
    {
        $this->sendWithAuth(
            '/api/lapp/ram/policy/delete',
            [
                'accountId' => $accountId,
                'deviceSerial' => $deviceSerial
            ]
        );
    }
}
