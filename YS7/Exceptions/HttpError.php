<?php
namespace Neteast\YS7\Exceptions;

class HttpError extends ClientError
{
    public $response;

    public function __construct($response) {
        $this->response = $response;
        parent::__construct('', $response->statusCode);
    }
}