<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;

class CronTasksRunCommand extends ContainerAwareCommand
{
    private $output;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:crontasks:run')
            ->setDescription('Runs Cron Tasks if needed')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Running Cron Tasks...</comment>');

        $this->output = $output;
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $crontasks = $em->getRepository('AppBundle:CronTask')->findAll();

        foreach ($crontasks as $crontask) {
            // Get the last run time of this task, and calculate when it should run next
            $lastrun = $crontask->getLastRun() ? $crontask->getLastRun()->format('U') : 0;
            $nextrun = $lastrun + $crontask->getInterval();

            // We must run this task if:
            // * time() is larger or equal to $nextrun
            $run = (time() >= $nextrun);

            if ($run) {
                $output->writeln(sprintf('Running Cron Task <info>%s</info>', $crontask->getName()));

                // Set $lastrun for this crontask
                $crontask->setLastRun(new \DateTime());

                try {

                    $commands = $crontask->getCommands();
                    foreach ($commands as $command) {
                        $output->writeln(sprintf('Executing command <comment>%s</comment>...', $command));
                        $output->writeln(sprintf('Start <comment>%s</comment>', date("Y-m-d H:i:s")));
                        // Run the command
                        $this->runCommand($command);
                        $output->writeln(sprintf('Finish <comment>%s</comment>', date("Y-m-d H:i:s")));
                    }

                    $output->writeln('<info>SUCCESS</info>');
                } catch (\Exception $e) {
                    $output->writeln('<error>ERROR</error>');
                }

                // Persist crontask
                $em->persist($crontask);
            } else {
                $output->writeln(sprintf('Skipping Cron Task <info>%s</info>', $crontask->getName()));
            }
        }

        // Flush database changes
        $em->flush();

        $output->writeln('<comment>Done!</comment>');
    }

    private function runCommand($string)
    {
        // Split namespace and arguments
        $namespace = explode(' ', $string)[0];

        // Set input
        $command = $this->getApplication()->find($namespace);
        $input = new StringInput($string);

        // Send all output to the console
        $returnCode = $command->run($input, $this->output);

        return $returnCode != 0;
    }
}
