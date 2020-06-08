<?php

namespace App\Controller;


use App\Classes\AbstractConsumer;
use App\Interfaces\StrategyInterface;

class ConsumerController extends AbstractConsumer
{
    private $strategy;

    public function __construct(StrategyInterface $strategy, string $host, int $port, string $user, string $password)
    {
        $this->strategy = $strategy;
        parent::__construct($host, $port, $user, $password);
    }

    public function goProcess(): void
    {
        $this->channel->basic_consume($this->queue, '', false, true, false, false, [$this, 'handler']);
        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function handler($data)
    {
        // TODO: Implement handler() method.
        $this->strategy->handler($data);
    }
}