<?php
namespace Neteast\YS7\Clients\AI;

use Neteast\YS7\Client\AuthClient;

/**
 * AI智能 
 * 
 * @property HumanClient $human 人形检测
 */
class AIClient extends AuthClient
{
    protected $clients = [
        'human' => HumanClient::class
    ];
}