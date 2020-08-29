<?php
namespace Neteast\YS7\Http;

class PreparedRequest
{
    public $method;

    public $baseUrl;

    public $uri;

    public $headers;

    public $data;

    public function __construct($method, $baesUrl, $uri, $headers, $data) {
        $this->method = $method;
        $this->baseUrl = $baesUrl;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->data = $data;
    }

    public function make(Curl $ch = null) {
        $ch = $ch?: Curl::init();
        $headers = [];
        foreach($this->headers as $key => $value) {
            $headers[] = $key . ': ' . $value;
        }
        $ch->setopt(CURLOPT_URL, $this->baseUrl . $this->uri);
        $ch->setopt(CURLOPT_HTTPHEADER, $headers);
        $ch->setopt(CURLOPT_CUSTOMREQUEST, $this->method);
        $ch->setopt(CURLOPT_HEADER, 1);
        $ch->setopt(CURLOPT_POSTFIELDS, $this->data);
        return $ch;
    }
}