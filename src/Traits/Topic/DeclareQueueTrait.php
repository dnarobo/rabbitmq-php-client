<?php

namespace Dna\RabbitMq\Traits\Topic;

trait DeclareQueueTrait
{
    protected function declareExchange()
    {
        $this->channel->exchange_declare(
            $this->exchange_name,
            'topic',
            false, false, false
        );
    }
}