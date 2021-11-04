<?php

namespace Neteast\YS7\Clients\MQ;

use Neteast\YS7\Clients\BaseClient;

/**
 * 消息订阅
 * https://open.ys7.com/doc/zh/book/index/mq_service.html
 *
 * @property ConsumerClient $consumer
 */
class MQClient extends BaseClient
{
    protected $clients = [
        'consumer' => ConsumerClient::class
    ];
}
