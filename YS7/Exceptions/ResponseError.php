<?php
namespace Neteast\YS7\Exceptions;

class ResponseError extends ClientError
{
    public $statusCode;

    public $data;

    public function __construct($code, $message, $data, $statusCode) {
        parent::__construct($message, $code);

        $this->statusCode = $statusCode;
        $this->data = $data;
    }
}