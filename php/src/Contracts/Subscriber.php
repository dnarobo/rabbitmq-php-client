<?php

namespace Dna\RabbitMq\Contracts;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Subscriber Class in RabbitMq Pub/Sub Pattern
 * 발행자/구독자 패턴의 구독자 클래스
 */
abstract class Subscriber
{
    protected $req_queue_name = '';
    protected $exchange_name  = 'dna';
    protected $vhost          = '/';
    
    /**
     * @var PhpAmqpLib\Connection\AbstractConnection
     */
    protected $connection;

    /**
     * @var PhpAmqpLib\Channel\AbstractChannel
     */
    protected $channel;

    /**
     * Implementing Process using Template Pattern.
     * 템플릿 패턴을 이용해 절차 구현
     * 1. 연결
     * 2. 채널
     * 3. 교환기 선언
     * 4. 큐 선언 
     * 5. 라우팅 키 바인딩
     * @return void
     */
    protected function init(){
        
        $this->connection = $this->initConnection();
        $this->channel    = $this->connection->channel();
        
        $this->declareExchange();
        $this->declareQueue();

        $route_keys = $this->bindRouteKeys();
        
        foreach ($route_keys as $route_key) {
            $this->channel->queue_bind(
                $this->queue_name,
                $this->exchange_name,
                $binding_key
            );
        }
        
    }

    /**
     * initializing and return $this->connection
     * @return PhpAmqpLib\Connection\AbstractConnection $connection
     */
    abstract protected function initConnection();

    protected function declareExchange()
    {
        $this->channel->exchange_declare(
            $this->exchange_name,
            'topic',
            false, false, false
        );
    }

    protected function declareQueue()
    {
        list($this->queue_name, ,) = 
        $this->channel->queue_declare($this->req_queue_name, false, false, true, false);
    }

    /**
     * @return iterable of string
     */
    abstract protected function bindRouteKeys();
    
    abstract protected function handle(AMQPMessage $msg);

    /**
     * @param bool $no_local
     * @param bool $no_ack
     * @param bool $exclusive
     * @param bool $nowait
     * @param int|null $ticket
     * @param array $arguments
     * * @param string $consumer_tag
     */
    public function listen(
        $no_local = false,
        $no_ack = true,
        $exclusive = false,
        $nowait = false,
        $ticket = null,
        $arguments = [],
        $consumer_tag = ''
    ){
        
        $this->init();

        $client = $this;

        $this->channel->basic_consume(
            $this->queue_name,
            $consumer_tag,
            $no_local,
            $no_ack,
            $exclusive,
            $nowait,
            function(AMQPMessage $msg) use ($client) {
                $client->handle($msg);
            },
            $arguments
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function close(){
        if($this->channel)
            $this->channel->close();

        if($this->connection)
            $this->connection->close();
    }
}
