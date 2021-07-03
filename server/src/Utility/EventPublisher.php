<?php

namespace App\Utility;

use Exception;
use WebSocket\Client;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../.env');

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

    /**
     * @param string $email
     * @param string $subject
     * @param string $body
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendEmail(string $email, string $subject, string $body): void
    {
        try {
            $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
            $mailer = new Mailer($transport);
            $email = (new Email())
                ->from($_ENV['MAILER_FROM'])
                ->to($email)
                ->subject($subject)
                ->html($body);
            $mailer->send($email);
        } catch (Exception $e) {
            return;
        }
    }
}
