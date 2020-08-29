<?php
namespace Neteast\YS7;

use Neteast\YS7\Auth\Auth;
use Neteast\YS7\YS7Client;

/**
 * 可认证客户端
 */
class YS7Auth extends Auth
{
    private $appKey;

    private $appSecret;

    public function __construct($appKey, $appSecret)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
    }

    public function getAppKey()
    {
        return $this->appKey;
    }

    public function getAppSecret()
    {
        return $this->appSecret;
    }

    public function isRefreshImplemented()
    {
        return true;
    }

    public function refresh(YS7Client $client)
    {
        $client->token->get($this);
    }

    protected function getAccessTokenKey()
    {
        $appKey = md5($this->getAppKey() . ' ' . $this->getAppSecret());
        return "ys7:app:$appKey:accesstoken";
    }
}