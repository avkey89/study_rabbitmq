<?php

namespace App\Strategy;


use App\Interfaces\StrategyInterface;

class SubscriptionStrategy implements StrategyInterface
{

    public function handler($data)
    {
        // TODO: Implement handler() method.
        echo " [x] Received (".date('d.m.Y H:i').") ", $data->body, "\n";
        $json = json_decode($data->body, true);
        $mailer = new \Swift_Mailer(new \Swift_SmtpTransport());
        $send_mail = (new \Swift_Message($json["theme"]))
            ->setFrom('noreply@avkey-web.ru')
            ->setTo($json["user"]["email"])
            ->setBody(
                $json["message"]
                , 'text/plain');
        $resultSend = $mailer->send($send_mail);

        file_get_contents(HOST . "/api/v1/rabbitmaillog?subid=".$json["user"]["id"]."&disid=".$json["distribution_id"]."&status=".($resultSend ? 'send' : 'error'));

        echo " [x] Send ended (".date('d.m.Y H:i').") ", "\n";
        $data->delivery_info['channel']->basic_ack($data->delivery_info['delivery_tag']);

    }
}