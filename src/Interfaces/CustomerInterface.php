<?php

namespace App\Interfaces;


interface CustomerInterface
{
    public function getQueue(string $name): void;

    public function close(): void;
}