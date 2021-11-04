<?php

namespace Neteast\YS7;

use Neteast\YS7\Clients\BaseClient;
use Neteast\YS7\Message\Consumer;
use Shisa\HTTPClient\Auth\AbstractAuth;

/**
 * YS7Client
 *
 * @property Clients\AI\AIClient $ai AI
 * @property Clients\Device\DeviceClient $device 设备
 * @property Clients\EZOpen $ezopen ezopen
 * @property Clients\LiveClient $live 直播
 * @property Clients\MQ\MQClient $mq 消息
 * @property Clients\RAM\RAMClient $ram 子账户
 * @property Clients\TokenClient $token 令牌
 */
class YS7Client extends BaseClient
{
    protected $baseUrl = 'https://open.ys7.com';

    protected $clients = [
        'ai' => Clients\AI\AIClient::class,
        'device' => Clients\Device\DeviceClient::class,
        'ezopen' => Clients\EZOpen::class,
        'live' => Clients\LiveClient::class,
        'mq' => Clients\MQ\MQClient::class,
        'ram' => Clients\RAM\RAMClient::class,
        'token' => Clients\TokenClient::class
    ];

    public static function create(AbstractAuth $auth = null)
    {
        return new static($auth);
    }

    public function __construct(AbstractAuth $auth = null)
    {
        parent::__construct($auth);
        $this->setBaseClient($this);
        $auth->setClient($this);
    }

    private $consumers = [];

    /**
     * 消费者
     * @param mixed $group 1-5
     * @return Consumer
     */
    public function consumer($group = 1)
    {
        if (!isset($this->consumers[$group])) {
            $this->consumers[$group] = new Consumer($this, $group);
        }
        return $this->consumers[$group];
    }
}
