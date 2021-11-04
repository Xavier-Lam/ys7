<?php

namespace Neteast\YS7\Cache;

use Cml\Model;
use Psr\SimpleCache\CacheInterface;

class CmlCache implements CacheInterface
{
    public static function singleton()
    {
        static $session;
        if (!$session) {
            $session = new static();
        }
        return $session;
    }

    public function get($key, $default = null)
    {
        $rv = $this->_cache()->get($key);
        if ($rv === false) {
            $rv = $default;
        }
        return $rv;
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->_cache()->set($key, $value, $ttl ?: 0);
    }

    public function delete($key)
    {
        return $this->_cache()->delete($key);
    }

    public function clear()
    {
        return $this->_cache()->truncate();
    }

    public function getMultiple($keys, $default = null)
    {
        $rv = [];
        foreach ($keys as $key) {
            $rv[$key] = $this->get($key, $default);
        }
        return $rv;
    }

    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }

    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has($key)
    {
        return $this->_cache()->get($key) !== false;
    }

    protected function _cache()
    {
        return Model::staticCache();
    }
}
