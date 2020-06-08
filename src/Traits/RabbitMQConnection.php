<?php

namespace App\Traits;


use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

trait RabbitMQConnection
{
    private function connection(string $host, int $port, string $user, string $password): AbstractConnection
    {
        return new AMQPStreamConnection($host, $port, $user, $password);
    }
}