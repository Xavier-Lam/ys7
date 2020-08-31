<?php
namespace Neteast\YS7\Http;

/**
 *
 * @version 1.0
 * @author Xavier-Lam
 */
class Curl
{
    private $ch;
    private $options = [];

    public static function init($url = null)
    {
        return new static($url);
    }

    public static function strerror($errornum)
    {
        return curl_strerror($errornum);
    }

    public static function version($age = CURLVERSION_NOW)
    {
        return curl_version($age);
    }

    public function close()
    {
        return curl_close($this->getCh());
    }

    public function copyHandle()
    {
        $ch = curl_copy_handle($this->getCh());
        $rv = new static();
        $rv->ch = $ch;
        return $rv;
    }

    public function errno()
    {
        return curl_errno($this->getCh());
    }

    public function error()
    {
        return curl_error($this->getCh());
    }

    public function escape(string $str)
    {
        return curl_escape($this->getCh(), $str);
    }

    public function exec()
    {
        $method = $this->getopt(CURLOPT_CUSTOMREQUEST);
        $url = $this->getinfo(CURLINFO_EFFECTIVE_URL);
        $body = $this->getopt(CURLOPT_POSTFIELDS);
        $this->beforeExec($url, $method, $body);

        $ch = $this->getCh();
        $rv = curl_exec($ch);

        $err = $this->errno($ch);
        $status = $this->getinfo(CURLINFO_HTTP_CODE);
        $contentType = $this->getinfo(CURLINFO_CONTENT_TYPE);
        $this->afterExec($url, $method, $body, $rv, $err, $status, $contentType);

        return $rv;
    }

    public function getinfo($opt = null)
    {
        return curl_getinfo($this->getCh(), $opt);
    }

    public function pause($bitmask)
    {
        return curl_pause($this->getCh(), $bitmask);
    }

    public function reset()
    {
        $this->options = [];
         curl_reset($this->getCh());
        $this->setopt(CURLOPT_CUSTOMREQUEST, 'GET');
    }

    public function setopt($option, $value)
    {
        switch($option) {
            case CURLOPT_NOBODY:
                // 	TRUE 时将不输出 BODY 部分。同时 Mehtod 变成了 HEAD
                $value && $this->setopt(CURLOPT_CUSTOMREQUEST, 'HEAD');
                break;
            case CURLOPT_HTTPGET:
                return $this->setopt(CURLOPT_CUSTOMREQUEST, 'GET');
            case CURLOPT_POST:
                // TRUE 时会发送 POST 请求，类型为：application/x-www-form-urlencoded
                $this->setopt(CURLOPT_CUSTOMREQUEST, 'POST');
            case CURLOPT_PUT:
                return $this->setopt(CURLOPT_CUSTOMREQUEST, 'PUT');
        }
        $this->options[$option] = $value;
        return curl_setopt($this->getCh(), $option, $value);
    }

    public function setoptArr(array $options)
    {
        $this->options = array_merge($this->options, $options);
        return curl_setopt_array($this->getCh(), $options);
    }

    public function unescape(string $str)
    {
        return curl_unescape($this->getCh(), $str);
    }

    public function hasopt($option)
    {
        return isset($this->options[$option]);
    }

    public function getopt($option)
    {
        return $this->hasopt($option)? $this->options[$option]: null;
    }

    private $funcs = [];

    public function addBeforeExec($func)
    {
        $this->funcs['beforeExec'][] = $func;
    }

    public function addAfterExec($func)
    {
        $this->funcs['afterExec'][] = $func;
    }

    protected function beforeExec($url, $method, $body)
    {
        foreach($this->funcs['beforeExec'] as $func) {
            $func($url, $method, $body);
        }
        // 可以设置一些代理之类的
    }

    protected function afterExec($url, $method, $body, $result, $err, $status, $contentType)
    {
        foreach($this->funcs['afterExec'] as $func) {
            $func($url, $method, $body, $result, $err, $status, $contentType);
        }
    }

    protected function getCh()
    {
        return $this->ch;
    }

    private function __construct($url = null)
    {
        $this->ch = curl_init($url);
        $this->reset();
        $this->setopt(CURLOPT_URL, $url);
        $this->setopt(CURLOPT_RETURNTRANSFER, 1);
    }
}