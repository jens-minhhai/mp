<?php

namespace Command\Auth;

use Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TokenCommand extends Command
{
    protected function configure()
    {
        $this->setName('auth:token')
             ->setDescription('Auth master password')
             ->addArgument('domain', InputArgument::REQUIRED, 'Domain name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domain = $input->getArgument('domain');

        $token = Factory::load('controller.auth.token')->get($domain);
        $output->writeln("<question>\n\n{$token}\n</question>");
    }
}
