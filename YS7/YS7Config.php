<?php
namespace Neteast\YS7;

use Psr\SimpleCache\CacheInterface;
use Neteast\YS7\Cache\DummyCache;

/**
 * 萤石配置
 */
class YS7Config
{
    private static $_cache;

    /**
     * @return CacheInterface
     */
    public static function getCache()
    {
        if(!isset(static::$_cache)) {
            static::$_cache = new DummyCache();
        }
        return static::$_cache;
    }

    public static function setCache(CacheInterface $cache)
    {
        static::$_cache = $cache;
    }
}