<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Image;

    require dirname(__DIR__) . '/vendor/autoload.php';

    #$server = IoServer::factory(
    #    new Chat(),
    #    8080
    #);
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Image()
            )
        ),
        8082
    );
    $server->run();
    echo "aaa";
	print_r($server->factory);
