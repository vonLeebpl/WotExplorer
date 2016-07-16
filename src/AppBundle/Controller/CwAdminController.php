<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Controller;

use AppBundle\Utils\WotManipulator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CwAdminController
 * @package AppBundle\Controller
 * @Route(path="/cwadmin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class CwAdminController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route(path="/refreshclans", name="refresh_clans")
     *
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
        $this->addFlash('success', 'Clans refreshed');
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
        $this->addFlash('success', 'Maps refreshed');
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
        $this->addFlash('success', 'Tanks refreshed');
        return $this->redirectToRoute('homepage');
    }
}
