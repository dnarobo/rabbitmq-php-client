<?php

namespace Dna\RabbitMq\Traits\Topic;

use PhpAmqpLib\Message\AMQPMessage;

trait PublishTrait
{
    /**
     * Publishes a message
     *
     * @param AMQPMessage $msg
     * @param string $routing_key
     * @param bool $mandatory
     * @param bool $immediate
     * @param int $ticket
     */
    public function publish(
        AMQPMessage $msg,
        string $routing_key ='',
        bool $mandatory = false,
        bool $immediate = false,
        int $ticket = null
    ){
        $this->init();
        $this->channel->basic_publish(
            $msg,
            $this->exchange_name,
            $routing_key,
            $mandatory,
            $immediate,
            $ticket
        );
    }
}