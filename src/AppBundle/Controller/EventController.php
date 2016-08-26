<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * @Route("/event")
 */
class EventController extends BaseController
{

    /**
     * @Route("/show/{clan_id}", name="showclanactiveevent")
     * @Method("GET")
     */
    public function showClanAction($clan_id)
    {
        // find out if we have active event
        $event = $this->getEntityManager()->getRepository('AppBundle:Event')->isActiveEvent();

        if (!$event)
        {
            $this->addFlash('info', 'Sorry, no active event currently');
            return $this->redirectToRoute('homepage');
        }

        $clan = $this->getEntityManager()->getRepository('AppBundle:Clan')->findOneByTag($clan_id);
        if (!$clan)
        {
            $this->addFlash('error', sprintf('No such clan: %s', $clan_id));
            return $this->redirectToRoute('homepage');
        }

        $mdata = $this->getEntityManager()->getRepository('AppBundle:EventAccountData')
            ->findBy(array('clan' => $clan->getId(), 'event' => $event->getId()), array('fame_points' => 'DESC', 'rank' => 'ASC'));

        return $this->render('event/showclan.html.twig', array(
            'event' => $event,
            'mdata' => $mdata,
            'clan' => $clan,
        ));
    }
}
