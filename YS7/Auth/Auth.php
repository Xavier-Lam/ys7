<?php
namespace Neteast\YS7\Auth;

use Neteast\YS7\YS7Client;
use Neteast\YS7\YS7Config;
use Neteast\YS7\Exceptions\ResponseError;

abstract class Auth
{
    private $accesstoken;

    public function isAvailable()
    {
        return !!$this->getAccessToken();
    }

    public function isInvalidAuth(ResponseError $e)
    {
        return $e->getCode() === 10002;
    }

    public function isRefreshImplemented()
    {
        return false;
    }

    public function refresh(YS7Client $client)
    {
        throw new \RuntimeException('not impletmented');
    }

    public function setAccessToken($accessToken, $expireTime = null)
    {
        if($expireTime) {
            $expiresIn = ($expireTime - time()*1000)/1000;
        } else {
            $expiresIn = 7*60*60;
        }
        $accessTokenKey = $this->getAccessTokenKey();
        if($accessTokenKey) {
            YS7Config::getCache()
                ->set($accessTokenKey, $accessToken, $expiresIn);
        }
        $this->accesstoken = $accessToken;
    }

    public function getAccessToken()
    {
        if(!$this->accesstoken) {
            $accessTokenKey = $this->getAccessTokenKey();
            if($accessTokenKey) {
                $this->accesstoken = YS7Config::getCache()
                    ->get($accessTokenKey);
            }
        }
        return $this->accesstoken;
    }

    protected function getAccessTokenKey()
    {
        return null;
    }
}