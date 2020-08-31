<?php
namespace Neteast\YS7;

use Neteast\YS7\Client\AuthClient;
use Neteast\YS7\Message\Consumer;

/**
 * YS7Client
 *
 * @property Clients\AI\AIClient $ai AI
 * @property Clients\Device\DeviceClient $device 设备
 * @property Clients\LiveClient $live 直播
 * @property Clients\MQ\MQClient $mq 消息
 * @property Clients\RAM\RAMClient $ram 子账户
 * @property Clients\TokenClient $token 令牌
 */
class YS7Client extends AuthClient
{
    protected $baseUrl = 'https://open.ys7.com';

    protected $clients = [
        'ai' => Clients\AI\AIClient::class,
        'device' => Clients\Device\DeviceClient::class,
        'live' => Clients\LiveClient::class,
        'mq' => Clients\MQ\MQClient::class,
        'ram' => Clients\RAM\RAMClient::class,
        'token' => Clients\TokenClient::class
    ];

    public function __construct($auth = null)
    {
        parent::__construct($auth);
        $this->setBaseClient($this);
    }

    private $consumers = [];

    /**
     * 消费者
     * @param mixed $group 1-5
     * @return Consumer
     */
    public function consumer($group = 1)
    {
        if(!isset($this->consumers[$group])) {
            $this->consumers[$group] = new Consumer($this, $group);
        }
        return $this->consumers[$group];
    }
}