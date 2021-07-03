<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use WebSocket\WebSocket;

require dirname(__DIR__) . '/websocket/vendor/autoload.php';

$loop   = React\EventLoop\Factory::create();

$webSocketServer = new React\Socket\Server('0.0.0.0:5001', $loop);
$server = new IoServer(
    new HttpServer(
        new WsServer(
            new WebSocket()
        )
    ),
    $webSocketServer
);

$loop->run();
