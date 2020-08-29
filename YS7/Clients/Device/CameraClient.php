<?php
namespace Neteast\YS7\Clients\Device;

use Neteast\YS7\Client\AuthClient;

/**
 * https://open.ys7.com/doc/zh/book/index/device_select.html
 */
class CameraClient extends AuthClient
{
    /**
     * 获取摄像头列表
     * https://open.ys7.com/doc/zh/book/index/device_select.html#device_select-api3
     */
    public function list($pageStart = 0, $pageSize = 50)
    {
        return $this->sendWithAuth('/api/lapp/camera/list', [
            'pageStart' => $pageStart,
            'pageSize' => $pageSize,
        ])->json()['data'];
    }

    /**
     * 获取指定设备的通道信息
     * https://open.ys7.com/doc/zh/book/index/device_select.html#device_select-api6
     * @param mixed $deviceSerial
     * @return mixed
     */
    public function listByDevice($deviceSerial)
    {
        return $this->sendWithAuth('/api/lapp/device/camera/list', [
            'deviceSerial' => $deviceSerial
        ])->json()['data'];
    }
}