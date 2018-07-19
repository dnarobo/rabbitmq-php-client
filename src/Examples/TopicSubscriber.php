<?php

namespace Dna\RabbitMq\Examples;

use Dna\RabbitMq\Contracts\Topic\Subscriber as BaseSubscriber;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

use Dna\RabbitMq\Traits\Topic\CloseTrait;
use Dna\RabbitMq\Traits\Topic\DeclareExchangeTrait;
use Dna\RabbitMq\Traits\Topic\DeclareQueueTrait;

class TopicSubscriber extends BaseSubscriber
{
    use CloseTrait;
    use DeclareExchangeTrait;
    use DeclareQueueTrait;
    
    public function __construct() {
        $this->req_queue_name = 'example_queue_name';
        $this->exchange_name  = 'example_ex_name';
        $this->vhost          = '/';
    }

    protected function initConnection() : AbstractConnection
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest',$this->vhost);
        return $this->connection;
    }
    
    protected function handle(AMQPMessage $msg)
    {
        $body        = $msg->body;
        $routing_key = $msg->delivery_info['routing_key'];
        
        print("$routing_key : $body\n");
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