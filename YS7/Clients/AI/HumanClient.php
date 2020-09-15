<?php
namespace Neteast\YS7\Clients\AI;

use Neteast\YS7\Clients\BaseClient;

/**
 * 人体人形识别
 * https://open.ys7.com/doc/zh/book/index/ai/body.html
 */
class HumanClient extends BaseClient
{
    /**
     * 检测人数
     * https://open.ys7.com/doc/zh/book/index/ai/body.html#body-api2
     * @param mixed $image
     * @return int 
     */
    public function detectNum($image)
    {
        return $this->sendWithAuth('/api/lapp/intelligence/human/analysis/detect', [
            'image' => $this->handleImage($image),
            'dataType' => 1,
            'operation' => 'number'
        ])->json()['data']['number'];
    }

    /**
     * 标记人坐标
     * https://open.ys7.com/doc/zh/book/index/ai/body.html#body-api2
     * @param mixed $deviceSerial
     * @param mixed $direction
     * @param mixed $channelNo
     * @param mixed $speed
     */
    public function detectRect($image)
    {
        return $this->sendWithAuth('/api/lapp/intelligence/human/analysis/detect', [
            'image' => $this->handleImage($image),
            'dataType' => 1,
            'operation' => 'rect'
        ])->json()['data']['locations'];
    }

    protected function handleImage($image)
    {
        if(is_string($image)) {
            $image = file_get_contents($image);
        }
        return base64_encode($image);
    }
}