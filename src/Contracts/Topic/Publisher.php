<?php

namespace Dna\RabbitMq\Contracts\Topic;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AbstractConnection;
use Dna\RabbitMq\Contracts\Client;

abstract class Publisher extends Client
{
    
}