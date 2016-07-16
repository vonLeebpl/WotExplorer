<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshTanksCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('app:refresh:tanks')
            ->setDescription('Refresh tanks from WOT API')
            ->setHelp(<<<EOT
The <info>app:refresh:tanks</info> command refreshes WOT tanks from Wargaming API:

  <info>php bin/console app:refresh:tanks</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manipulator = $this->getContainer()->get('app.utils.wot_manipulator');
        $manipulator->refreshWotTanks();

        $output->writeln('All tanks were refreshed.');
    }
}
