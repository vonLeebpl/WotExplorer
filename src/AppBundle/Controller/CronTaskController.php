<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Controller;

use AppBundle\Entity\CronTask;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/crontasks")
 */
class CronTaskController extends BaseController
{
    /**
     * @Route("/addtasks", name="crontasks_add")
     */
    public function addAction()
    {
        $entity = new CronTask();

        $entity
            ->setName('PSQD active event clans refresh')
            ->setInterval(300) // Run once every 5 mins
            ->setCommands([
                    'app:refresh:clan_event_data PSQD',
                    'app:refresh:clan_event_data PSQDX',
                ]
            );

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        $this->addFlash('success', 'Cron tasks added!');
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/deletetasks", name="crontasks_delete")
     */
    public function deleteAction()
    {
        $rep = $this->getEntityManager()->getRepository('AppBundle:CronTask');
        $tasks = $rep->findAll();

        foreach ($tasks as $task)
        {
            $this->getEntityManager()->remove($task);
        }
        $this->getEntityManager()->flush();

        $this->addFlash('success', 'Cron tasks deleted!');
        return $this->redirectToRoute('homepage');
    }
}
