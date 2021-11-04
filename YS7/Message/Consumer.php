<?php

namespace Neteast\YS7\Message;

use Neteast\YS7\Exceptions\YS7Exception;
use Neteast\YS7\Message\DataObject\Message;
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
        $this->createConsumerId();
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
        try {
            $messages = $this->client->mq->consumer->getMessages($this->consumerId);
        } catch (YS7Exception $e) {
            if ($e->getCode() === "70101") {
                $this->createConsumerId();
                $messages = $this->client->mq->consumer->getMessages($this->consumerId);
            } else {
                throw $e;
            }
        }

        // TODO: try catch signals
        foreach ($messages as $message) {
            $message = Message::fromApi($message);
            foreach ($this->handlers as $handler) {
                $handler($message, $this, $this->client);
            }
        }

        $commit && $this->client->mq->consumer->commit($this->consumerId);
    }

    protected function createConsumerId()
    {
        return $this->consumerId = $this->client->mq->consumer->create();
    }
}
