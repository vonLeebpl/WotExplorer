<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshMapsCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('app:refresh:maps')
            ->setDescription('Refresh maps from WOT API')
            ->setHelp(<<<EOT
The <info>app:refresh:maps</info> command refreshes WOT maps from Wargaming API:

  <info>php bin/console app:refresh:maps</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manipulator = $this->getContainer()->get('app.utils.wot_manipulator');
        $manipulator->refreshWotMaps();

        $output->writeln('All maps were refreshed.');
    }
}
