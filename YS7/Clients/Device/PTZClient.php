<?php
namespace Neteast\YS7\Clients\Device;

use Neteast\YS7\Clients\BaseClient;
use Neteast\YS7\Enum\PTZ;

/**
 * 云台
 * https://open.ys7.com/doc/zh/book/index/device_ptz.html
 * 
 * @property PresetClient $preset 预置点
 */
class PTZClient extends BaseClient
{
    protected $clients = [
        'preset' => PresetClient::class
    ];

    /**
     * 开始云台转动
     * https://open.ys7.com/doc/zh/book/index/device_ptz.html#device_ptz-api1
     * @param mixed $deviceSerial
     * @param mixed $direction
     * @param mixed $channelNo
     * @param mixed $speed
     */
    public function start($deviceSerial, $direction, $channelNo = 1, $speed = PTZ::SPEED_NORMAL)
    {
        $this->sendWithAuth('/api/lapp/device/ptz/start', [
            'deviceSerial' => $deviceSerial,
            'direction' => $direction,
            'channelNo' => $channelNo,
            'speed' => $speed
        ]);
    }

    /**
     * 停止云台转动
     * https://open.ys7.com/doc/zh/book/index/device_ptz.html#device_ptz-api2
     * @param mixed $deviceSerial
     * @param mixed $direction
     * @param mixed $channelNo
     * @param mixed $speed
     */
    public function stop($deviceSerial, $channelNo = 1)
    {
        $this->sendWithAuth('/api/lapp/device/ptz/stop', [
            'deviceSerial' => $deviceSerial,
            'channelNo' => $channelNo
        ]);
    }
}