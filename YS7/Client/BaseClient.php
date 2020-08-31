<?php
namespace Neteast\YS7\Client;

use Neteast\YS7\Exceptions\CurlError;
use Neteast\YS7\Exceptions\HttpError;
use Neteast\YS7\Exceptions\ResponseError;
use Neteast\YS7\Http\PreparedRequest;
use Neteast\YS7\Http\Request;
use Neteast\YS7\Http\Response;

abstract class BaseClient
{
    protected $baseClient;

    protected $baseUrl;

    protected $clients = [];

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function setBaseClient($client)
    {
        $this->baseClient = $client;
    }

    /**
     * @return Response
     */
    public function send($url, $method = 'GET', $data = [], $params = [], $headers = [], $extInfo = [])
    {
        $request = $this->createRequest($url, $method, $data, $params, $headers, $extInfo);
        $preparedRequest = $this->prepare($request, $extInfo);
        $response = $this->exec($preparedRequest);
        return $this->handleResponse($response, $request, $extInfo);
    }

    protected function getRequestClass()
    {
        return Request::class;
    }

    /**
     * @return Request
     */
    protected function createRequest($url, $method = 'GET', $data = [], $params = [], $headers = [], $extInfo = [])
    {
        if(!parse_url($url)['host']) {
            $url = $this->baseUrl . $url;
        }
        $cls = $this->getRequestClass();
        return new $cls($url, $method, $data, $params, $headers);
    }

    public function prepare(Request $request, $extInfo = [])
    {
        return $request->prepare($extInfo);
    }

    public function exec(PreparedRequest $preparedRequest)
    {
        $ch = $preparedRequest->make();
        $resp = $ch->exec();
        return new Response($ch, $resp);
    }

    protected function handleResponse(Response $response, Request $request, $extInfo = [])
    {
        if($response->errno) {
            throw new CurlError($response->error, $response->errno);
        }

        if(!$response->isSuccess()) {
            throw new HttpError($response);
        }

        $json = $response->json();
        if($json['code'] === '200') {
            return $response;
        }

        if(isset($json['code'])) {
            throw new ResponseError(
                $json['code'],
                $json['msg'],
                $json['data'],
                $response->statusCode);
        } else {
            throw new ResponseError(
                -500,
                'invalid response',
                [],
                $response->statusCode);
        }
    }

    /**
     * 生成子client
     */
    protected function createSubClient($cls, $args = [])
    {
        $reflectionClass = new \ReflectionClass($cls);
        $client = $reflectionClass->newInstanceArgs($args);
        $client->setBaseUrl($this->baseUrl);
        $client->setBaseClient($this->baseClient);
        return $client;
    }

    public function __get($name)
    {
        if(!array_key_exists($name, $this->clients)) {
            throw new \InvalidArgumentException('invalid api client: ' . $name);
        }
        if(is_string($this->clients[$name])) {
            $cls = $this->clients[$name];
            $this->clients[$name] = $this->createSubClient($cls);
        }
        return $this->clients[$name];
    }

    public function __call($name, $args)
    {
        if(!array_key_exists($name, $this->clients)) {
            throw new \InvalidArgumentException('invalid api client: ' . $name);
        }
        $key = $name . implode(',', array_map(spl_object_hash, $args));
        if(!isset($this->clients[$key])) {
            $cls = $this->clients[$name];
            $this->clients[$key] = $this->createSubClient($cls, $args);
        }
        return $this->clients[$key];
    }
}