<?php

namespace Dna\RabbitMq\Examples;

use Dna\RabbitMq\Contracts\TopicSubscriber as BaseSubscriber;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;

class TopicSubscriber extends BaseSubscriber
{
    protected function initConnection() : AbstractConnection
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        return $this->connection;
    }
    
    protected function handle(AMQPMessage $msg)
    {
        $body        = json_decode($msg->body,true);
        $routing_key = $msg->delivery_info['routing_key'];
        
        print("$routing_key : $body");
    }

    protected function bindRouteKeys() : array 
    {
        return [
            '*.orange.*',
            '*.*.rabbit',
            'lazy.#'
        ];
    }
}