<?php

ini_set("display_errors", 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use App\Controller\ProducerController;

try {
    $newQueue = new ProducerController($config["rabbitIP"], $config["rabbitPort"], $config["rabbitLogin"], $config["rabbitPassword"]);

    if (!empty($_POST["type"])) {
        switch ($_POST["type"]) {
            case 'simple':
                if (empty($_POST["message"])) {
                    throw new \Exception('Message is not exists' . "\n");
                }
                $newQueue->createQueue('simple');
                $newQueue->message(filter_input(INPUT_POST, "message", FILTER_SANITIZE_SPECIAL_CHARS));
                $newQueue->close();
                echo "Message '{$_POST["message"]}' add in queue";
                break;

            default:
                break;
        }
    } else {
        throw new \Exception('you have connection, but didn\'t send queue type' . "\n");
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}