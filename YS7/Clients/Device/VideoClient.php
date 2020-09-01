<?php
namespace Neteast\YS7\Clients\Device;

use Neteast\YS7\Client\AuthClient;

/**
 * https://open.ys7.com/doc/zh/book/index/device.html
 */
class VideoClient extends AuthClient
{
    public function records($deviceSerial, $startTime = null, $endTime = null, $channelNo = 1, $recType = 0)
    {
        $req = [
            'deviceSerial' => $deviceSerial,
            'channelNo' => $channelNo,
            'recType' => $recType
        ];
        if($startTime) {
            $req['startTime'] = $startTime;
        }
        if($endTime) {
            $req['endTime'] = $endTime;
        }
        return $this->sendWithAuth('/api/lapp/video/by/time', $req)->json()['data']?: [];
    }
}