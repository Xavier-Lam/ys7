<?php
namespace Neteast\YS7\Message;

use Neteast\YS7\YS7Client;

/**
 * 消费
 */
class Consumer
{
    protected $client;

    protected $group;

    protected $consumerId;

    public function __construct(YS7Client $client, $group = 1)
    {
        $this->client = $client;
        $this->group = $group;
        $this->consumerId = $this->client->mq->consumer->create();
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getConsumerId()
    {
        return $this->consumerId;
    }

    private $handlers = [];

    public function addHandler($handler)
    {
        $this->handlers[] = $handler;
    }

    public function consume($commit = true)
    {
        $messages = $this->client->mq->consumer->getMessages($this->consumerId);

        // TODO: try catch signals
        foreach($messages as $message) {
            foreach($this->handlers as $handler) {
                $handler($message, $this, $this->client);
            }
        }

        $commit && $this->client->mq->consumer->commit($this->consumerId);
    }
}