<?php

namespace App\RestApi;


use App\RestApi\Controller\SubscriptionLog;

class RestApiRoutes
{
    public function rabbitMailLog($subid, $disid, $status)
    {
        $subLog = new SubscriptionLog();
        return ["response" => $subLog->log($subid, $disid, $status)];
    }
}