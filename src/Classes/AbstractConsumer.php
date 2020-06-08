<?php

namespace App\Classes;


use App\Interfaces\CustomerInterface;
use App\Traits\RabbitMQConnection;

abstract class AbstractConsumer implements CustomerInterface
{
    use RabbitMQConnection;

    protected $connect;

    protected $channel;

    protected $queue;

    public function __construct(string $host, int $port, string $user, string $password)
    {
        $this->connect = $this->connection($host, $port, $user, $password);
    }

    public function getQueue(string $name): void
    {
        // TODO: Implement getQueue() method.
        $this->queue = $name;
        if ($this->connect) {
            $this->channel = $this->connect->channel();
            $this->channel->queue_declare($this->queue, false, true, false, false);
        } else {
            throw new \Exception('Not found RabbitMQ connection');
        }
    }

    public function close(): void
    {
        // TODO: Implement close() method.
        $this->channel->close();
        $this->connect->close();
    }
}