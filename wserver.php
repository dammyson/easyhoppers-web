<?php

use App\Services\Mobile;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

    require 'public/index.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Mobile()
            )
        ),
        8111
    );

    $server->run();