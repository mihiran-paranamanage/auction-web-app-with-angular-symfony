<?php

namespace App\Command;

use App\Repository\ConfigRepository;
use App\Repository\EmailQueueRepository;
use App\Utility\EventPublisher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

/**
 * Class SendEmailsCommand
 * @package App\Command
 */
class SendEmailsCommand extends Command
{
    private $emailQueueRepository;
    private $configRepository;
    private $eventPublisher;

    protected static $defaultName = 'app:send-emails';

    /**
     * Configure
     */
    protected function configure(): void
    {
        $this->setDescription('Sending emails . . .');
    }

    /**
     * @return EventPublisher
     */
    public function getEventPublisher() : EventPublisher {
        if (!($this->eventPublisher instanceof EventPublisher)) {
            $this->eventPublisher = new EventPublisher($this->emailQueueRepository, $this->configRepository);
        }
        return $this->eventPublisher;
    }

    /**
     * @param EventPublisher $eventPublisher
     */
    public function setEventPublisher(EventPublisher $eventPublisher) {
        $this->eventPublisher = $eventPublisher;
    }

    /**
     * SendEmailsCommand constructor.
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        EmailQueueRepository $emailQueueRepository,
        ConfigRepository $configRepository
    )
    {
        parent::__construct();
        $this->emailQueueRepository = $emailQueueRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->getEventPublisher()->sendEmails();
            $output->writeln('Emails Sent Successfully!');
            return Command::SUCCESS;
        } catch (Exception $e) {
            return Command::FAILURE;
        }
    }
}
