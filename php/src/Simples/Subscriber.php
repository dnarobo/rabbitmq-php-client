<?php

namespace Dna\RabbitMq\Simples;

use Dna\RabbitMq\Subscriber as BaseSubscriber;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Subscriber extends BaseSubscriber
{
    protected function initConnection()
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    }
    
    protected function handle(AMQPMessage $msg)
    {
        $body        = json_decode($msg->body,true);
        $routing_key = $msg->delivery_info['routing_key'];
        print("$routing_key : $body");
    }
}