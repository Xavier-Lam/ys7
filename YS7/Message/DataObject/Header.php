<?php
namespace Neteast\YS7\Message\DataObject;


/**
 * @property int $channelNo
 * @property string $deviceId
 * @property string $messageId
 * @property \DateTime $messageTime
 * @property string $type
 */
class Header
{
    private $data = [];

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __set($name, $value)
    {
        if($name === 'messageTime') {
            $value = intval($value/1000);
            $value = new \DateTime("@{$value}");
        }
        $this->data[$name] = $value;
    }
}
