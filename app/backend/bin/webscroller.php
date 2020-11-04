<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat2;

    require dirname(__DIR__) . '/vendor/autoload.php';

    #$server = IoServer::factory(
    #    new Chat(),
    #    8080
    #);
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat2()
            )
        ),
        8081
    );

    $server->run();
