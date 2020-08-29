<?php
namespace Neteast\YS7\Cache;

use Psr\SimpleCache\CacheInterface;

class DummyCache implements CacheInterface
{
    public function get($key, $default = null)
    {
        return null;
    }

    public function set($key, $value, $ttl = null)
    {

    }

    public function delete($key)
    {

    }

    public function clear()
    {

    }

    public function getMultiple(iterable $keys, $default = null)
    {

    }

    public function setMultiple(iterable $values, $ttl = null)
    {

    }

    public function deleteMultiple(iterable $keys)
    {

    }

    public function has($key)
    {

    }
}