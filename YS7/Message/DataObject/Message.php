<?php

namespace Neteast\YS7\Message\DataObject;

/**
 * @property Header $header
 * @property object $body
 * @property Picture[] $pictureList
 */
class Message
{
    public const TYPE_ALARM = 'ys.alarm';
    public const TYPE_ONOFFLINE = 'ys.onoffline';

    public static function fromApi($data)
    {
        $rv = new static();
        $header = new Header();
        foreach ($data['header'] as $key => $value) {
            $header->$key = $value;
        }
        $rv->header = $header;
        $rv->body = (object)$data['body'];
        $pictureList = [];
        foreach ($data['pictureList'] as $picture) {
            $pictureList[] = (object)$picture;
        }
        $rv->pictureList = $pictureList;
        return $rv;
    }
}
