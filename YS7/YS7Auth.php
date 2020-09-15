<?php
namespace Neteast\YS7;

use Neteast\YS7\Auth\BaseAuth;

/**
 * 可认证客户端
 */
class YS7Auth extends BaseAuth
{
    private $appKey;

    private $appSecret;

    public static function create($appKey, $appSecret)
    {
        return new static($appKey, $appSecret);
    }

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

    public function auth()
    {
        $this->getClient()->token->get($this);
    }

    protected function getAccessTokenKey()
    {
        $appKey = md5($this->getAppKey() . ' ' . $this->getAppSecret());
        return "ys7:app:$appKey:accesstoken";
    }
}