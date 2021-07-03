<?php

namespace App\Utility;

use WebSocket\Client;

require dirname(__DIR__) . '/../vendor/autoload.php';

/**
 * Class EventPublisher
 * @package App\Utility
 */
class EventPublisher
{
    /**
     * @param string $topic
     * @param string $msg
     * @throws \WebSocket\BadOpcodeException
     */
    public function publishToWS(string $topic, string $msg = '') {
        $client = new Client('ws://172.16.238.14:5001/' . $topic);
        $client->send($msg);
        $client->close();
    }
}
