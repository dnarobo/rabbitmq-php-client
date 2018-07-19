<?php

namespace Dna\RabbitMq\Traits\Topic;

trait CloseTrait
{
    public function close(){
        if($this->channel)
            $this->channel->close();

        if($this->connection)
            $this->connection->close();
    }
}