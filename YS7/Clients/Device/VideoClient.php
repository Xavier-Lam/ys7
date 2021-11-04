<?php

namespace Neteast\YS7\Clients\Device;

use Neteast\YS7\Clients\BaseClient;

/**
 * https://open.ys7.com/doc/zh/book/index/device.html
 */
class VideoClient extends BaseClient
{
    public function records($deviceSerial, $startTime = null, $endTime = null, $channelNo = 1, $recType = 0)
    {
        $req = [
            'deviceSerial' => $deviceSerial,
            'channelNo' => $channelNo,
            'recType' => $recType
        ];
        if ($startTime) {
            $req['startTime'] = $startTime * 1000;
        }
        if ($endTime) {
            $req['endTime'] = $endTime * 1000;
        }
        return $this->sendWithAuth('/api/lapp/video/by/time', $req)->json()['data'] ?: [];
    }
}
