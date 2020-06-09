<?php

namespace App\Classes;


use App\Interfaces\ProducerInterface;
use App\Traits\RabbitMQConnection;
use PhpAmqpLib\Message\AMQPMessage;

abstract class AbstractProducer implements ProducerInterface
{
    use RabbitMQConnection;

    private $connect;

    private $channel;

    private $queue;

    public function __construct(string $host, int $port, string $user, string $password)
    {
        $this->connect = $this->connection($host, $port, $user, $password);
    }

    public function createQueue(string $name): void
    {
        // TODO: Implement createQueue() method.
        $this->queue = $name;
        if ($this->connect) {
            $this->channel = $this->connect->channel();
            $this->channel->queue_declare($this->queue, false, true, false, false);
        } else {
            throw new \Exception('Not found RabbitMQ connection');
        }
    }

    public function message(string $msg, array $params = []): void
    {
        // TODO: Implement message() method.
        $sendMessage = new AMQPMessage($msg, $params);
        $this->channel->basic_publish($sendMessage, '', $this->queue);
    }

    public function close()
    {
        // TODO: Implement close() method.
        $this->channel->close();
        $this->connect->close();
    }
}