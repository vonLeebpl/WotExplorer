<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshEventsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:refresh:events')
            ->setDescription('Refresh events data from WOT API')
            ->setHelp(<<<EOT
The <info>app:refresh:events</info> command refreshes all events details from Wargaming API:

  <info>php bin/console app:refresh:events</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manipulator = $this->getContainer()->get('app.utils.wot_manipulator');
        $manipulator->refreshEvents();

        $output->writeln('All events were refreshed.');
    }
}
