<?php
namespace Neteast\YS7\Client;

use Neteast\YS7\Auth\Auth;
use Neteast\YS7\Exceptions\ResponseError;
use Neteast\YS7\Http\Request;
use Neteast\YS7\Http\Response;

/**
 * 可认证客户端
 */
class AuthClient extends BaseClient
{
    /**
     * @var Auth
     */
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->setAuth($auth);
    }

    public function setAuth(Auth $auth = null)
    {
        $this->auth = $auth;
        foreach($this->clients as $client) {
            if($client instanceof AuthClient) {
                $client->setAuth($auth);
            }
        }
    }

    public function sendWithAuth($url, $data = [], $method = 'POST', $params = [], $headers = [], $extInfo = [])
    {
        $extInfo['auth'] = true;
        return $this->send($url, $method, $data, $params, $headers, $extInfo);
    }

    public function prepare(Request $request, $extInfo = [])
    {
        if($extInfo['auth']) {
            // 预查auth是否有效
            !$this->auth->isAvailable() && $this->auth->refresh($this->baseClient);

            $request->data['accessToken'] = $this->auth->getAccessToken();
        }
        return parent::prepare($request, $extInfo);
    }

    protected function handleResponse(Response $response, Request $request, $extInfo = [])
    {
        try {
            return parent::handleResponse($response, $request, $extInfo);
        }
        catch(ResponseError $e) {
            if($extInfo['auth'] && $this->auth->isRefreshImplemented() && $this->auth->isInvalidAuth($e)) {
                // auth失效,重新获取
                $this->auth->refresh($this->baseClient);
                $preparedRequest = $this->prepare($request, $extInfo);
                $response = $this->exec($preparedRequest);
                return parent::handleResponse($response, $request, $extInfo);
            }
            throw $e;
        }
    }

    protected function createSubClient($cls, $args = [])
    {
        $reflectedCls = new \ReflectionClass($cls);
        if($reflectedCls->isSubclassOf(AuthClient::class)) {
            $args = array_merge([$this->auth], $args);
        }
        return parent::createSubClient($cls, $args);
    }
}