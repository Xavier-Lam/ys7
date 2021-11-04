<?php

namespace Neteast\YS7\Clients\Device;

use Neteast\YS7\Clients\BaseClient;

/**
 * https://open.ys7.com/doc/zh/book/index/device_select.html
 */
class CameraClient extends BaseClient
{
    /**
     * 设备抓拍图片
     * https://open.ys7.com/doc/zh/book/index/device_option.html#device_option-api4
     * @param mixed $deviceSerial
     * @return mixed
     */
    public function capture($deviceSerial, $channelNo = 1)
    {
        return $this->sendWithAuth('/api/lapp/device/capture', [
            'deviceSerial' => $deviceSerial,
            'channelNo' => $channelNo
        ])->json()['data']['picUrl'];
    }

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
     * 获取设备状态信息
     * https://open.ys7.com/doc/zh/book/index/device_select.html#device_select-api5
     * @param mixed $deviceSerial
     * @return mixed
     */
    public function status($deviceSerial, $channelNo = 1)
    {
        return $this->sendWithAuth('/api/lapp/device/status/get', [
            'deviceSerial' => $deviceSerial,
            'channelNo' => $channelNo
        ])->json()['data'];
    }
}
