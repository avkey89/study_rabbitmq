<?php

ini_set("display_errors", 1);

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config.php';

use RestService\Server;
use App\RestApi\RestApiRoutes;

Server::create('/', new RestApiRoutes())
    ->setDebugmode(true)

    ->addGetRoute('rabbitmaillog', 'rabbitMailLog')

->run();