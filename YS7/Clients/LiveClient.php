<?php
namespace Neteast\YS7\Clients;

/**
 * 直播
 * https://open.ys7.com/doc/zh/book/index/address.html
 */
class LiveClient extends BaseClient
{
    /**
     * 获取直播地址
     * https://open.ys7.com/doc/zh/book/index/address.html#address-api2
     * @param mixed $deviceSerial
     * @param mixed $expiresIn 过期秒数 5分钟到720天之间
     * @param mixed $channelNo
     * @return mixed
     */
    public function address($deviceSerial, $expiresIn = null, $channelNo = 1)
    {
        $req = [
            'deviceSerial' => $deviceSerial,
            'channelNo' => $channelNo
        ];
        if($expiresIn) {
            $req['expireTime'] = $expiresIn;
        }
        return $this->sendWithAuth('/api/lapp/live/address/limited', $req)->json()['data'];
    }

    /**
     * 开通直播功能
     * @param mixed $deviceSerial
     * @param mixed $channelNo
     * @return mixed
     */
    public function open($deviceSerial, $channelNo = 1)
    {
        return $this->batchOpen(["$deviceSerial:$channelNo"])[0];
    }

    /**
     * 批量开通直播功能
     * https://open.ys7.com/doc/zh/book/index/address.html#address-api3
     * @param mixed $data 直播源，[设备序列号]:[通道号],[设备序列号]:[通道号]的形式，例如427734222:1,423344555:3，均采用英文符号，限制50个
     * @return mixed
     */
    public function batchOpen($data)
    {
        $source = implode(',', $data);
        return $this->sendWithAuth('/api/lapp/live/video/open', ['source' => $source])->json()['data'];
    }

    /**
     * 批量获取直播地址
     * https://open.ys7.com/doc/zh/book/index/address.html#address-api4
     * @param mixed $data
     * @return mixed
     */
    public function batchGet($data)
    {
        $source = implode(',', $data);
        return $this->sendWithAuth('/api/lapp/live/address/get', ['source' => $source])->json()['data'];
    }

    /**
     * 关闭直播功能
     * @param mixed $deviceSerial
     * @param mixed $channelNo
     * @return mixed
     */
    public function close($deviceSerial, $channelNo = 1)
    {
        return $this->batchClose(["$deviceSerial:$channelNo"])[0];
    }

    /**
     * 批量关闭直播功能
     * https://open.ys7.com/doc/zh/book/index/address.html#address-api5
     * @param mixed $data
     * @return mixed
     */
    public function batchClose($data)
    {
        $source = implode(',', $data);
        return $this->sendWithAuth('/api/lapp/live/video/close', ['source' => $source])->json()['data'];
    }
}