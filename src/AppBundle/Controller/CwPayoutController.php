<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Clan;
use AppBundle\Utils\PayoutManipulator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class CwPayoutController
 * @package AppBundle\Controller
 * @Route("/cwtracker/payout")
 */
class CwPayoutController extends BaseController
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @Route("/new/{clan}", name="new_payout")
     * @param Clan $clan
     * @return Response
     */
    public function newPayoutAction(Clan $clan)
    {
        /** @var PayoutManipulator $pm */
        $pm = $this->container->get('app.utils.payout_manipulator');
        $pm->initializePayout($clan, 10000);

        return new Response('OK');
    }
}
