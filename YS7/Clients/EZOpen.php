<?php

namespace Neteast\YS7\Clients;

/**
 * EZOPEN协议
 * https://open.ys7.com/doc/zh/readme/ezopen.html
 */
class EZOpen extends BaseClient
{
    public const TYPE_LIVE = 'live';
    public const TYPE_REC = 'rec';

    public static $defaultOptions = [
        'resolution' => '',  // 清晰度,空标准,hd高清
        'source' => '', //回放源,自动选择（缺省值）、本地存储（local）、云存储（cloud）
        'baseUrl' => 'ezopen://open.ys7.com/',
    ];

    public function wssUrl($deviceSerial, $channelNo = 1, $type = self::TYPE_LIVE, $options = [])
    {
        $url = $this->getRawEZUrl($deviceSerial, $channelNo, $type, $options);
        $data = $this->sendWithAuth('/api/lapp/live/url/ezopen', [
            'ezopen' => $url,
            'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?: 'Neteast/YS7',
            'isFlv' => 'false',
            'addressTypes' => 'null',
            'isHttp' => 'false',
        ])->json();
        $wssUrl = $data['data'];
        $token = $data['ext']['token'];
        if ($type === self::TYPE_LIVE) {
            return $wssUrl . '&auth=1&biz=4&cln=100&ssn=' . $token;
        } else {
            return $wssUrl . '&auth=1&cln=100&ssn=' . $token;
        }
    }

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
     * @param int $begin 开始时间时间戳
     * @param int $end 结束时间时间戳
     * @return string
     */
    public function rec($deviceSerial, $channelNo = 1, $begin = null, $end = null, $options = [])
    {
        if ($begin) {
            $options['begin'] = (new \DateTime("@$begin"))->format('YmdHis');
        }
        if ($end) {
            $options['end'] = (new \DateTime("@$end"))->format('YmdHis');
        }
        return $this->getEZUrl($deviceSerial, $channelNo, '.rec', $options);
    }

    public function getRawEZUrl($deviceSerial, $channelNo = 1, $type = self::TYPE_LIVE, $options = [])
    {
        $options = array_merge(static::$defaultOptions, $options);

        // 重拼baseurl
        $url = $options['baseUrl'];
        unset($options['baseUrl']);
        if ($options['validateCode']) {
            list($protocol, $host) = explode("://", $url);
            $url = "$protocol://{$options['validateCode']}@$host";
            unset($options['validateCode']);
        }

        // 拼接url
        $url .= "$deviceSerial/$channelNo";
        if ($options['resolution']) {
            $url .= '.' . $options['resolution'];
        }
        unset($options['resolution']);
        $url .= "." . $type;

        if ($querystr = http_build_query($options)) {
            $url .= '?' . $querystr;
        }

        return $url;
    }

    private function getEZUrl($deviceSerial, $channelNo, $type, $options = [])
    {
        $options = array_merge(static::$defaultOptions, $options);
        $url = $options['baseUrl'] . $deviceSerial . '/' . $channelNo;
        if ($options['resolution']) {
            $url .= '.' . $options['resolution'];
            unset($options['resolution']);
        }
        if ($options['source']) {
            $url .= '.' . $options['source'];
            unset($options['source']);
        }
        $url .= $type;
        unset($options['baseUrl']);
        if ($querystr = http_build_query($options)) {
            $url .= '?' . $querystr;
        }

        return $url;
    }
}
