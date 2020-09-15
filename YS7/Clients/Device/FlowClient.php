<?php
namespace Neteast\YS7\Clients\Device;

use Neteast\YS7\Clients\BaseClient;

/**
 * 客流统计
 * https://open.ys7.com/doc/zh/book/index/device_flow.html
 */
class FlowClient extends BaseClient
{
    /**
     * 获取客流统计开关
     * https://open.ys7.com/doc/zh/book/index/device_flow.html#device_flow-api1
     * @param mixed $deviceSerial
     * @return mixed
     */
    public function getStatus($deviceSerial, $channelNo = null)
    {
        // TODO: 可能是找子序列号 不是填channelNo
        $req = [
            'deviceSerial' => $deviceSerial
        ];
        if($channelNo) {
            $req['channelNo'] = $channelNo;
        }
        return $this->sendWithAuth('/api/lapp/passengerflow/switch/status', $req)
            ->json()['data'];
    }

    /**
     * 设置客流统计开关
     * https://open.ys7.com/doc/zh/book/index/device_flow.html#device_flow-api2
     * @param mixed $deviceSerial
     * @param mixed $enable
     * @param mixed $channelNo
     */
    public function setStatus($deviceSerial, $enable = true, $channelNo = null)
    {
        $req = [
            'deviceSerial' => $deviceSerial,
            'enable' => $enable? 1: 0
        ];
        if($channelNo) {
            $req['channelNo'] = $channelNo;
        }
        return $this->sendWithAuth('/api/lapp/passengerflow/switch/set', $req)
            ->json()['data'];
    }

    /**
     * 查询设备某一天的统计客流数据
     * https://open.ys7.com/doc/zh/book/index/device_flow.html#device_flow-api3
     * @param mixed $deviceSerial
     * @param mixed $channelNo
     * @param \DateTime|null $date
     * @return mixed
     */
    public function statDaily($deviceSerial, $channelNo = 1, \DateTime $date = null)
    {
        $req = [
            'deviceSerial' => $deviceSerial,
            'channelNo' => $channelNo
        ];
        if($date) {
            $req['date'] = strtotime($date->format('Y-m-d 00:00:00')) * 1000;
        }
        return $this->sendWithAuth('/api/lapp/passengerflow/daily', $req)
            ->json()['data'];
    }

    /**
     * 查询设备某一天每小时的统计客流数据
     * https://open.ys7.com/doc/zh/book/index/device_flow.html#device_flow-api4
     * @param mixed $deviceSerial
     * @param mixed $channelNo
     * @param \DateTime|null $date
     * @return mixed
     */
    public function statHourly($deviceSerial, $channelNo = 1, \DateTime $date = null)
    {
        $req = [
            'deviceSerial' => $deviceSerial,
            'channelNo' => $channelNo
        ];
        if($date) {
            $req['date'] = $date->getTimestamp() * 1000;
        }
        return $this->sendWithAuth('/api/lapp/passengerflow/hourly', $req)
            ->json()['data'];
    }
}