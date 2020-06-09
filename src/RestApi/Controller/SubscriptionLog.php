<?php

namespace App\RestApi\Controller;


class SubscriptionLog
{
    public function log($subid, $disid, $status)
    {
        global $Database;
        $result = $Database->insert(
            "distribution_logs",
            [
                "subscriberid" => $subid,
                "distributionid" => $disid,
                "status" => "'".$status."'",
            ]
        );

        if ($result > 0) {
            return true;
        }

        return false;
    }
}