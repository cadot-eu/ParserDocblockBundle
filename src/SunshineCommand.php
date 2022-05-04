<?php

namespace Cadoteu\ParserDocblockBundle;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SunshineCommand extends Command
{
    protected static $defaultName = 'app:sunshine';
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        // you *must* call the parent constructor
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Good morning!');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Waking up the sun');
        echo '================================';

        return Command::SUCCESS;
    }
}
