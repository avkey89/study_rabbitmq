<?php

ini_set("display_errors", 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use App\Controller\ConsumerController;
use App\Strategy\SubscriptionStrategy;

try {
    $newQueue = new ConsumerController(new SubscriptionStrategy(), $config["rabbitIP"], $config["rabbitPort"], $config["rabbitLogin"], $config["rabbitPassword"]);
    echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
    $newQueue->getQueue("subscription");
    $newQueue->goProcess(false, false, false, false, true);
    $newQueue->close();
} catch (\Exception $e) {
    echo $e->getMessage();
}