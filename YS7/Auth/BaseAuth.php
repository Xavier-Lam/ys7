<?php
namespace Neteast\YS7\Auth;

use Shisa\HTTPClient\Auth\AbstractAuth;
use Shisa\HTTPClient\Exceptions\ResponseError;
use Neteast\YS7\YS7Config;
use Shisa\HTTPClient\HTTP\Request;

abstract class BaseAuth extends AbstractAuth
{
    private $accesstoken;

    public function isAvailable()
    {
        return !!$this->getAccessToken();
    }

    public function isInvalidAuthError(ResponseError $e)
    {
        return $e->getCode() === 10002;
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

    public function authRequest(Request $request)
    {
        $request->data['accessToken'] = $this->getAccessToken();
        return $request;
    }

    protected function getAccessTokenKey()
    {
        return null;
    }
}