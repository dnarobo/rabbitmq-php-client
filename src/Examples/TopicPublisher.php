<?php

namespace Dna\RabbitMq\Examples;

use Dna\RabbitMq\Contracts\Topic\Publisher as BasePublisher;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

use Dna\RabbitMq\Traits\Topic\CloseTrait;
use Dna\RabbitMq\Traits\Topic\DeclareExchangeTrait;
use Dna\RabbitMq\Traits\Topic\DeclareQueueTrait;
use Dna\RabbitMq\Traits\Topic\PublishTrait;

class TopicPublisher extends BasePublisher
{
    use CloseTrait;
    use DeclareExchangeTrait;
    use DeclareQueueTrait;
    use PublishTrait;
    
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

}