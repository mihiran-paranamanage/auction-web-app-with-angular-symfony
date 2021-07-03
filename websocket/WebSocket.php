<?php

namespace WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Class WebSocket
 * @package WebSocket
 */
class WebSocket implements MessageComponentInterface {

    /**
     * @var \SplObjectStorage 
     */
    private $clients;

    /**
     * WebSocket constructor.
     */
    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $subscriberCount = count($this->clients);
        echo "New connection: ({$conn->resourceId})\n";
        echo "Total subscriber count: {$subscriberCount}\n";
    }

    /**
     * @param ConnectionInterface $from
     * @param $msg
     */
    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if (
                $from->httpRequest->getUri()->getPath() === $client->httpRequest->getUri()->getPath() &&
                $from !== $client
            ) {
                $client->send($msg);
            }
        }
        $subscriberCount = count($this->clients);
        echo "Message published to {$subscriberCount} subscriber(s) for the topic: {$from->httpRequest->getUri()->getPath()}\n";
        echo "Message: $msg\n";
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        $subscriberCount = count($this->clients);
        echo "Connection {$conn->resourceId} has been disconnected!\n";
        echo "Total subscriber count: {$subscriberCount}\n";
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
