<?php

namespace App\Interfaces;


interface ProducerInterface
{
    public function createQueue(string $name): void;

    public function message(string $msg): void;

    public function close();
}