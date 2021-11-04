<?php

namespace Neteast\YS7\Clients;

use Shisa\HTTPClient\Clients\AuthClient;
use Shisa\HTTPClient\Clients\RecursiveClientMixin;
use Shisa\HTTPClient\Formatters\UrlEncodeFormatter;
use Shisa\HTTPClient\HTTP\Request;
use Shisa\HTTPClient\HTTP\Response;
use Neteast\YS7\Exceptions\YS7Exception;

class BaseClient extends AuthClient
{
    use RecursiveClientMixin;

    public function __construct($auth = null)
    {
        parent::__construct($auth);
        $this->setFormatter(new UrlEncodeFormatter());
    }

    protected function handleResponse(Response $response, Request $request, $options = [])
    {
        $response = parent::handleResponse($response, $request, $options);

        $json = $response->json();
        if ($json['code'] === '200') {
            return $response;
        }

        if (isset($json['code'])) {
            throw new YS7Exception(
                $json['code'],
                $json['msg'],
                $response
            );
        } else {
            throw new YS7Exception(
                -500,
                'invalid response',
                $response
            );
        }
    }
}
