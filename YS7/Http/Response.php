<?php
namespace Neteast\YS7\Http;

class Response
{
    public $statusCode;

    public $headers = [];

    public $content;

    public $errno;

    public $error;

    public function __construct($ch, $raw) {
        $this->statusCode = intval($ch->getinfo(CURLINFO_HTTP_CODE));

        $headersSize = $ch->getinfo(CURLINFO_HEADER_SIZE);
        $headersStr = substr($raw, 0, $headersSize);
        $headersArray = preg_split('/\\r\\n|\\n|\\r/', $headersStr);
        foreach($headersArray as $header) {
            list($key, $value) = preg_split('/:\\s*/', $header, 2);
            if($key && $value) {
                $this->headers[$key] = $value;
            }
        }

        $this->content = substr($raw, $headersSize);
        $this->errno = $ch->errno();
        $this->error = $ch->error();
    }

    public function isSuccess() {
        return $this->statusCode >= 200 && $this->statusCode < 400;
    }

    private $_json;
    private $_jsonParsed;
    public function json($options = 0) {
        if(!$this->_jsonParsed) {
            $this->_jsonParsed = true;
            $this->_json = json_decode($this->content, true, 512, $options);
        }
        return $this->_json;
    }
}