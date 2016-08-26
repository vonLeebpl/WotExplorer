<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Controller;

use AppBundle\Entity\PayoutConfig;
use AppBundle\Form\Type\PayoutConfigType;
use AppBundle\Utils\WotManipulator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CwAdminController
 * @package AppBundle\Controller
 * @Route(path="/cwadmin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class CwAdminController extends BaseController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route(path="/refreshevents", name="refresh_events")
     */
    public function refreshEventsAction()
    {
        $wot_man = $this->container->get('app.utils.wot_manipulator');
        try {
            $wot_man->refreshEvents();
        }
        catch (\Exception $e)
        {
            throw new \Exception('Failed refreshing events. Contact admin to solve the issue.');
        }
        $this->addFlash('success', 'All events refreshed!');
        return $this->redirectToRoute('homepage');
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route(path="/refreshclans", name="refresh_clans")
     */
    public function refreshClansAction()
    {
        $wot_man = $this->container->get('app.utils.wot_manipulator');
        try {
            $wot_man->refreshClans();
        }
        catch (\Exception $e)
        {
            throw new \Exception('Failed refreshing clans. Contact admin to solve the issue.');
        }
        $this->addFlash('success', 'All clans refreshed!');
        return $this->redirectToRoute('homepage');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route(path="/refreshmaps", name="refresh_maps")
     */
    public function refreshMapsAction()
    {
        $wot_man = $this->container->get('app.utils.wot_manipulator');
        try {
            $wot_man->refreshWotMaps();
        }
        catch (\Exception $e)
        {
            throw new \Exception('Failed refreshing maps. Contact admin to solve the issue.');
        }
        $this->addFlash('success', 'Maps refreshed!');
        return $this->redirectToRoute('homepage');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route(path="/refreshtanks", name="refresh_tanks")
     */
    public function refreshTanksAction()
    {
        $wot_man = $this->container->get('app.utils.wot_manipulator');
        try {
            $wot_man->refreshWotTanks();
        }
        catch (\Exception $e)
        {
            throw new \Exception('Failed refreshing tanks. Contact admin to solve the issue.');
        }
        $this->addFlash('success', 'Tanks refreshed!');
        return $this->redirectToRoute('homepage');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="/editpayoutconfig", name="edit_payout_config")
     */
    public function editPayoutConfig(Request $request)
    {
        $config = $this->getEntityManager()->getRepository('AppBundle:PayoutConfig')->find(1);
        if (null === $config)
            $config = new PayoutConfig();

        $form = $this->createForm(PayoutConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid())
        {
            $this->getEntityManager()->persist($config);
            $this->getEntityManager()->flush();

            $this->addFlash('success', 'Payout config saved!');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('cwtracker/editPayoutConfig.html.twig', array(
            'form' => $form->createView(),
            'panel' => array(
                'title' => 'Payout config',
                'small_title' => 'edit payout settings',
            ),
        ));
    }
}
