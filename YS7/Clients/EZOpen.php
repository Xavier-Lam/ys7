<?php
namespace Neteast\YS7\Clients;

use Neteast\YS7\Client\AuthClient;

/**
 * EZOPEN协议
 * https://open.ys7.com/doc/zh/readme/ezopen.html
 */
class EZOpen extends AuthClient
{
    public static $defaultOptions = [
        'resolution' => '',  // 清晰度,空标准,hd高清
        'baseUrl' => 'ezopen://open.ys7.com/',
    ];

    /**
     * 获取直播地址
     * @return string
     */
    public function live($deviceSerial, $channelNo = 1, $options = [])
    {
        return $this->getEZUrl($deviceSerial, $channelNo, '.live', $options);
    }

    /**
     * 录制地址
     * @param int $start 开始时间时间戳
     * @param int $end 结束时间时间戳
     * @return string
     */
    public function rec($deviceSerial, $channelNo = 1, $start = null, $end = null, $options = [])
    {
        if($start) {
            $options['start'] = (new \DateTime("@$start"))->format('YmdHis');
        }
        if($end) {
            $options['end'] = (new \DateTime("@$end"))->format('YmdHis');
        }
        return $this->getEZUrl($deviceSerial, $channelNo, '.rec', $options);
    }

    private function getEZUrl($deviceSerial, $channelNo, $type, $options = [])
    {
        $options = array_merge(static::$defaultOptions, $options);
        $videoId = $this->getVideoId($deviceSerial, $channelNo);
        $url = $options['baseUrl'] . $videoId;
        if($options['resolution']) {
            $url .= '.' . $options['resolution'];
        }
        $url .= $type;
        unset($options['baseUrl']);
        unset($options['resolution']);
        if($querystr = http_build_query($options)) {
            $url .= '?' . $querystr;
        }
        return $url;
    }

    private function getVideoId($deviceSerial, $channelNo)
    {
        $data = $this->baseClient->live->address($deviceSerial, null, $channelNo);
        $originAddress = $data['liveAddress'];
        $originPath = parse_url($originAddress, PHP_URL_PATH);
        $filename = array_pop((explode('/', $originPath)));
        return explode('.', $filename)[0];
    }
}