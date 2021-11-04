<?php

namespace Neteast\YS7\Policy;

/**
 * 资源
 */
class Resource
{
    public const TYPE_DEV = 'dev';
    public const TYPE_CAM = 'cam';

    public $type;
    public $deviceSerial;
    public $channelNo;

    public static function create($deviceSerial, $channelNo = null)
    {
        return new static($deviceSerial, $channelNo);
    }

    public function __construct($deviceSerial, $channelNo = null)
    {
        $this->deviceSerial = $deviceSerial;
        $this->channelNo = $channelNo;
        $this->type = $channelNo ? static::TYPE_CAM : static::TYPE_DEV;
    }

    public function __toString()
    {
        $rv = "{$this->type}:{$this->deviceSerial}";
        if ($this->channelNo) {
            $rv .= ":{$this->channelNo}";
        }
        return $rv;
    }
}
