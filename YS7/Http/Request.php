<?php
namespace Neteast\YS7\Http;

/**
 * @property $data
 * @property $params
 */
class Request
{
    public $method;

    public $scheme;

    public $host;

    public $path;

    private $_data;

    private $_params;

    public $headers;

    public function __construct($url, $method = 'GET', $data = [], $params = [], $headers = []) {
        $this->method = strtoupper($method);

        // 重新处理Url
        // 处理querystring
        $u = parse_url($url);
        $query = [];
        parse_str($u['query'], $query);
        if($this->isNoBodyMethod()) {
            $params = array_merge($params, $data);
            $data = [];
        }
        $params = array_merge($query, $params);

        $this->scheme = $u['scheme'];
        $this->host = $u['host'];
        $this->path = $u['path'];

        $this->_data = $data;
        $this->_params = $params;
        $this->headers = $headers;
    }

    public function prepare($extInfo = []) {
        list($baseUrl, $uri) = $this->prepareUrl($extInfo);
        $headers = $this->prepareHeaders($extInfo, $baseUrl, $uri);
        $data = $this->prepareData($extInfo, $headers, $baseUrl, $uri);

        return new PreparedRequest($this->method, $baseUrl, $uri, $headers, $data);
    }

    protected function prepareUrl($extInfo) {
        $baseUrl = "{$this->scheme}://{$this->host}";
        $uri = "{$this->path}";
        if($this->params) {
            $uri .= '?' . http_build_query($this->params);
        }
        return [$baseUrl, $uri];
    }

    protected function prepareHeaders($extInfo, $baseUrl, $uri) {
        $headers = $this->headers;
        if(!$this->isNoBodyMethod() && $this->data && is_array($this->data)) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }
        return $headers;
    }

    protected function prepareData($extInfo, $preparedHeaders, $baseUrl, $uri) {
        $data = '';
        if(!$this->isNoBodyMethod() && $this->data) {
            if(is_array($this->data)) {
                $data = http_build_query($this->data);
            } else {
                $data = $this->data;
            }
        }
        return $data;
    }

    private function isNoBodyMethod() {
        return in_array($this->method, ['HEAD', 'GET', 'DELETE']);
    }

    public function &__get($name) {
        if($name == 'data') {
            if($this->isNoBodyMethod()) {
                $rv = &$this->_params;
            } else {
                $rv = &$this->_data;
            }
            return $rv;
        }
        if($name == 'params') {
            $rv = &$this->_params;
            return $rv;
        }
        return parent::__get($name);
    }

    public function __set($name, $value) {
        if($name == 'data' && !$this->isNoBodyMethod()) {
            $this->_data = $value;
        }
        parent::__set($name, $value);
    }
}