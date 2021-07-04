<?php

namespace App\Utility;

use App\Entity\EmailQueue;
use App\Repository\ConfigRepository;
use App\Repository\EmailQueueRepository;
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
    const CONFIG_MAILER_DSN = 'MAILER_DSN';
    const CONFIG_MAILER_FROM = 'MAILER_FROM';
    const CONFIG_EMAIL_NOTIFICATION_ENABLED = 'email_notification_enabled';
    const WEB_SOCKET_URL = 'ws://172.16.238.14:5001/';

    private $emailQueueRepository;
    private $configRepository;

    /**
     * EventPublisher constructor.
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        EmailQueueRepository $emailQueueRepository,
        ConfigRepository $configRepository
    ) {
        $this->emailQueueRepository = $emailQueueRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * @param string $topic
     * @param string $msg
     * @throws \WebSocket\BadOpcodeException
     */
    public function publishToWS(string $topic, string $msg = '') {
        $client = new Client(self::WEB_SOCKET_URL . $topic);
        $client->send($msg);
        $client->close();
    }

    /**
     * @param string $email
     * @param string $subject
     * @param string $body
     */
    public function pushEmailToQueue(string $email, string $subject, string $body) : void
    {
        $emailQueue = new EmailQueue();
        $emailQueue->setEmail($email);
        $emailQueue->setSubject($subject);
        $emailQueue->setBody($body);
        $this->emailQueueRepository->pushEmailToQueue($emailQueue);
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendEmails(): void
    {
        $mailerDsn = $this->configRepository->getConfigValue(self::CONFIG_MAILER_DSN);
        $mailerFrom = $this->configRepository->getConfigValue(self::CONFIG_MAILER_FROM);
        $emailNotificationEnabled = $this->configRepository->getConfigValue(self::CONFIG_EMAIL_NOTIFICATION_ENABLED);

        if ($emailNotificationEnabled && $mailerDsn && $mailerFrom) {
            $queueEmails = $this->emailQueueRepository->findAll();
            foreach ($queueEmails as $queueEmail) {
                if ($queueEmail instanceof EmailQueue) {
                    $email = $queueEmail->getEmail();
                    $subject = $queueEmail->getSubject();
                    $body = $queueEmail->getBody();
                    $this->sendEmail($email, $subject, $body, $mailerDsn, $mailerFrom);
                    $this->emailQueueRepository->removeEmailFromQueue($queueEmail);
                }
            }
        }
    }

    /**
     * @param string $email
     * @param string $subject
     * @param string $body
     * @param string $mailerDsn
     * @param string $mailerFrom
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    protected function sendEmail(string $email, string $subject, string $body, string $mailerDsn, string $mailerFrom) : void
    {
        try {
            $transport = Transport::fromDsn($mailerDsn);
            $mailer = new Mailer($transport);
            $email = (new Email())
                ->from($mailerFrom)
                ->to($email)
                ->subject($subject)
                ->html($body);
            $mailer->send($email);
        } catch (Exception $e) {
            return;
        }
    }
}
