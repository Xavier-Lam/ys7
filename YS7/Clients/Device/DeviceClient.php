<?php
namespace Neteast\YS7\Clients\Device;

use Neteast\YS7\Client\AuthClient;

/**
 * 设备
 * https://open.ys7.com/doc/zh/book/index/device.html
 *
 * @property ConfigurationClient $configuration 配置
 * @property CameraClient $camera 摄像头
 * @property FlowClient $flow 人流
 * @property PTZClient $ptz 云台
 * @property VideoClient $video 视频
 */
class DeviceClient extends AuthClient
{
    protected $clients = [
        'camera' => CameraClient::class,
        'configuration' => ConfigurationClient::class,
        'flow' => FlowClient::class,
        'ptz' => PTZClient::class,
        'video' => VideoClient::class
    ];

    /**
     * 添加设备
     * https://open.ys7.com/doc/zh/book/index/device_option.html#device_option-api1
     * @param mixed $deviceSerial
     * @param mixed $validateCode
     * @return mixed
     */
    public function add($deviceSerial, $validateCode)
    {
        $this->sendWithAuth('/api/lapp/device/add', [
            'deviceSerial' => $deviceSerial,
            'validateCode' => $validateCode,
        ]);
    }

    /**
     * 删除设备
     * https://open.ys7.com/doc/zh/book/index/device_option.html#device_option-api2
     * @param mixed $deviceSerial
     * @return mixed
     */
    public function delete($deviceSerial)
    {
        $this->sendWithAuth('/api/lapp/device/delete', [
            'deviceSerial' => $deviceSerial
        ]);
    }

    /**
     * 获取设备列表
     * https://open.ys7.com/doc/zh/book/index/device_select.html#device_select-api1
     * @param mixed $pageStart
     * @param mixed $pageSize
     * @return mixed
     */
    public function list($pageStart = 0, $pageSize = 50)
    {
        return $this->sendWithAuth('/api/lapp/device/list', [
            'pageStart' => $pageStart,
            'pageSize' => $pageSize,
        ])->json()['data'];
    }

    /**
     * 获取单个设备信息
     * https://open.ys7.com/doc/zh/book/index/device_select.html#device_select-api2
     * @param mixed $deviceSerial
     * @return mixed
     */
    public function info($deviceSerial)
    {
        return $this->sendWithAuth('/api/lapp/device/info', [
            'deviceSerial' => $deviceSerial
        ])->json()['data'];
    }
}