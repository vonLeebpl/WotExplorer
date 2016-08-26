<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshClanEventDataCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:refresh:clan_event_data')
            ->setDescription('Refresh active event clan data')
            ->addArgument(
                'clan',
                InputArgument::REQUIRED,
                'Clan tag to refresh?'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // is active event
        $event = $em->getRepository('AppBundle:Event')->isActiveEvent();
        if (!$event)
        {
            $output->writeln(sprintf('No active event, aborting!'));
            return;
        }

        $clan_id = $input->getArgument('clan');
        $output->writeln(sprintf('Refreshing clan %s', $clan_id));

        $clan = $em->getRepository('AppBundle:Clan')->findOneByTag($clan_id);
        if (!$clan)
        {
            $output->writeln(sprintf('No such clan in database: %s, aborting!', $clan_id));
            return;
        }

        $wot_service = $this->getContainer()->get('app.utils.wot_manipulator');
        $wot_service->refreshClanEventData($clan, $event);

        $output->writeln(sprintf('Success, clan %s refreshed!', $clan_id));
    }
}
