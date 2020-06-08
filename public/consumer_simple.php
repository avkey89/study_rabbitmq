<?php

ini_set("display_errors", 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use App\Controller\ConsumerController;
use App\Strategy\SimpleStrategy;

try {
    $newQueue = new ConsumerController(new SimpleStrategy(), $config["rabbitIP"], $config["rabbitPort"], $config["rabbitLogin"], $config["rabbitPassword"]);
    echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
    $newQueue->getQueue("simple");
    $newQueue->goProcess();
    $newQueue->close();
} catch (\Exception $e) {
    echo $e->getMessage();
}