<?php

ini_set("display_errors", 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use App\Controller\ProducerController;
use App\Classes\Database;
use PhpAmqpLib\Message\AMQPMessage;

try {
    $newQueue = new ProducerController($config["rabbitIP"], $config["rabbitPort"], $config["rabbitLogin"], $config["rabbitPassword"]);

    if (!empty($_POST["type"]) || !empty($argv[1])) {
        switch ($_POST["type"] ?? $argv[1]) {
            case 'simple':
                if (empty($_POST["message"])) {
                    throw new \Exception('Message is not exists' . "\n");
                }
                $newQueue->createQueue('simple');
                $newQueue->message(filter_input(INPUT_POST, "message", FILTER_SANITIZE_SPECIAL_CHARS));
                $newQueue->close();
                echo "Message '{$_POST["message"]}' add in queue";
                break;

            case 'subscription':
                // ищу подписку. собираю подписчиков + формирую письмо для отправки
                $newQueue->createQueue('subscription');
                $db = Database::getInstance();
                $db->connect($config["database_host"], $config["database_login"], $config["database_password"]);
                $db->selectDB($config["database_name"]);
                $distribution = $db->select("SELECT * FROM distribution WHERE id = 1")->fetch();
                if (!empty($distribution)) {
                    $json = [];
                    $json["distribution_id"] = $distribution[0]["id"];
                    $json["theme"] = $distribution[0]["name"];
                    $subscribers = $db->select("SELECT * FROM subscriber WHERE subscriptionid = ".$distribution[0]["subscriptionid"])->fetch();

                    $typeSubscription = $db->select("SELECT name FROM subscriptions WHERE id = ".$distribution[0]["subscriptionid"])->fetch();
                    if ($typeSubscription) {
                        if ($typeSubscription[0]["name"] == "news") {
                            $newsList = $distribution = $db->select("SELECT * FROM news")->fetch();

                            if (!empty($newsList)) {
                                $message = '';
                                foreach($newsList as $news) {
                                    $message .= date_format(date_create($news["date_published"]), 'd.m.Y'). ". ".$news["title"]."\n".$news["text"]."\n\n";
                                }
                                $json["message"] = $message;
                            }
                        }
                    }

                    if (!empty($subscribers)) {
                        foreach($subscribers as $subscriber) {
                            $json["user"] = $subscriber;
                            $newQueue->message(json_encode($json), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
                            echo "Email '{$subscriber["email"]}' add in queue"."\n";
                        }
                    }
                }
                $newQueue->close();
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