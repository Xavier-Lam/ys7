<?php
namespace Neteast\YS7\Auth;

/**
 * 可认证客户端
 */
class YS7SubAuth extends Auth
{
    private $appKey;

    public function __construct($appKey, $accessToken)
    {
        $this->appKey = $appKey;
        $this->setAccessToken($accessToken);
    }

    public function getAppKey()
    {
        return $this->appKey;
    }
}