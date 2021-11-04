<?php

namespace Neteast\YS7\Clients\MQ;

use Neteast\YS7\Clients\BaseClient;

/**
 * 消费者
 */
class ConsumerClient extends BaseClient
{
    /**
     * 创建消费者
     * @param $group 1-5
     * @return string consumerId
     */
    public function create($group = 1)
    {
        return $this->sendWithAuth('/api/lapp/mq/v1/consumer/group' . $group)->json()['data']['consumerId'];
    }

    /**
     * 获取消息
     * @param mixed $consumerId
     * @param mixed $preCommit true为读时标记为消费
     * @return mixed
     */
    public function getMessages($consumerId, $preCommit = false)
    {
        $req = [
            'consumerId' => $consumerId,
        ];
        if ($preCommit) {
            $req['preCommit'] = 1;
        }
        return $this->sendWithAuth('/api/lapp/mq/v1/consumer/messages', $req)->json()['data'];
    }

    /**
     * 消息已读
     * @param mixed $consumerId
     */
    public function commit($consumerId)
    {
        $this->sendWithAuth('/api/lapp/mq/v1/consumer/offsets', [
            'consumerId' => $consumerId,
        ]);
    }
}
