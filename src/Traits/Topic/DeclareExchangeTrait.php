<?php

namespace Dna\RabbitMq\Traits\Topic;

trait DeclareExchangeTrait
{
    protected function declareQueue()
    {
        list($this->queue_name, ,) = 
        $this->channel->queue_declare($this->req_queue_name, false, false, true, false);
    }
}