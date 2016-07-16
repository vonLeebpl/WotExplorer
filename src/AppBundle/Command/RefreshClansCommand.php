<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshClansCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('app:refresh:clans')
            ->setDescription('Refresh clan data and clan members authorised to use this site from WOT API')
            ->setHelp(<<<EOT
The <info>app:refresh:clans</info> command refreshes clans details and clans members for clans authorised to use this site from Wargaming API:

  <info>php bin/console app:refresh:clans</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manipulator = $this->getContainer()->get('app.utils.wot_manipulator');
        $manipulator->refreshClans();

        $output->writeln('All clans were refreshed.');
    }
}
