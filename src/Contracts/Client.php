<?php

namespace Dna\RabbitMq\Contracts\Topic;
use  PhpAmqpLib\Connection\AbstractConnection;
abstract class Client
{
    protected $req_queue_name = '';
    protected $exchange_name  = 'dna';
    protected $vhost          = '/';

    /**
     * @var AbstractConnection
     */
    protected $connection;
    /**
     * @var PhpAmqpLib\Channel\AMQPChannel
     */
    protected $channel;
    
    protected function init(){
    
        $this->connection = $this->initConnection();
        $this->channel    = $this->connection->channel();
        
        $this->declareExchange();
        $this->declareQueue();

    }
    
    /**
     * initializing and return $this->connection
     * @return AbstractConnection $connection
     */    
    abstract protected function initConnection() : AbstractConnection;

    abstract protected function declareExchange();
    abstract protected function declareQueue();

    abstract public function close();
}