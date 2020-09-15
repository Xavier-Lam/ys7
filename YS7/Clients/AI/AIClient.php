<?php
namespace Neteast\YS7\Clients\AI;

use Neteast\YS7\Clients\BaseClient;

/**
 * AI智能 
 * 
 * @property HumanClient $human 人形检测
 */
class AIClient extends BaseClient
{
    protected $clients = [
        'human' => HumanClient::class
    ];
}