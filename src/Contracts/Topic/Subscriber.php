<?php

namespace Dna\RabbitMq\Contracts\Topic;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AbstractConnection;

use Dna\RabbitMq\Contracts\Client;
abstract class Subscriber extends Client
{
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
        
        parent::init();

        $route_keys = $this->bindRouteKeys();
        
        foreach ($route_keys as $binding_key) {
            $this->channel->queue_bind(
                $this->queue_name,
                $this->exchange_name,
                $binding_key
            );
        }
        
    }

    /**
     * @return iterable of string
     */
    abstract protected function bindRouteKeys() : array;
    
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
        $consumer_tag = '',
        $arguments = []
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
            $ticket = null,
            $arguments
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

}
