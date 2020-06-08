<?php

namespace App\Strategy;


use App\Interfaces\StrategyInterface;

class SimpleStrategy implements StrategyInterface
{

    public function handler($data)
    {
        // TODO: Implement handler() method.
        echo " [x] Received (".date('d.m.Y H:i').") ", $data->body, "\n";
    }
}