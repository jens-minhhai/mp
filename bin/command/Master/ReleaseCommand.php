<?php

namespace Command\Master;

use Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReleaseCommand extends Command
{
    protected function configure()
    {
        $this->setName('master:release')
             ->setDescription('Release master');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $flag = Factory::load('controller.master.release')->execute();
        if ($flag) {
            return $output->writeln('Finishing release.');
        }
        $output->writeln('Error, try again later.');
    }
}
