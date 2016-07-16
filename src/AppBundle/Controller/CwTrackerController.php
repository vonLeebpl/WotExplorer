<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Battle;
use AppBundle\Entity\BattleAttendance;
use AppBundle\Entity\Clan;
use AppBundle\Entity\Player;
use AppBundle\Entity\Replay;
use AppBundle\Form\Type\ReplayType;
use AppBundle\Utils\WotReplayUtils;
use AppBundle\Utils\WotReplayParser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CwTrackerController
 * @package AppBundle\Controller
 * @Route(path="/cwtracker")
 */
class CwTrackerController extends BaseController
{
    /**
     * @Route("/newbattlefromreplay", name="new_battle_from_replay")
     * @Security("has_role('ROLE_CREATE_BATTLE')")
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function newBattleFromReplayAction(Request $request)
    {
        $replay = new Replay();
        $form = $this->createForm(ReplayType::class, $replay);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            $this->getEntityManager()->persist($replay);
            
            $parser = new WotReplayParser();
            $filename = $replay->getReplayFile()->getRealPath();
            $results = $parser->parse($filename);
            $clan = WotReplayUtils::guessBattleClan($results);

            //check if we have already such a battle
            $arena = $this->getEntityManager()->getRepository('AppBundle:Battle')
                    ->findOneByArenaId($results['arena']);

            if($arena) {
                $form->addError(new FormError('This battle already exists in database!'));
                // for some reasons the file stays although replay is removed
                unlink($replay->getReplayFile()->getFileInfo(File::class)->getPathname());
                $this->getEntityManager()->remove($replay);

            }
            elseif (!in_array($clan, $this->container->getParameter('security_settings')['authorised_clans']))
            {
                $form->addError(new FormError('This battle do not belong to authorised clans for this site'));
                // for some reasons the file stays although replay is removed
                unlink($replay->getReplayFile()->getFileInfo(File::class)->getPathname());
                $this->getEntityManager()->remove($replay);
            }
            else {
                $wotMan = $this->get('app.utils.wot_manipulator');
                $battle = $wotMan->createBattleFromReplay($results, $this->getUser(), $replay);

                return $this->redirectToRoute('commander_for_battle', ['request' => $request, 'battle' => $battle->getId()]);
            }
        }

        return $this->render('cwtracker/new_replay.html.twig', array(
            'form' => $form->createView(),
            'panel' => [
                        'title' => 'Create battle from replay',
                        'small_title' => 'upload WOT replay file'
                        ]
            ));
    }

    /**
     * @param Request $request
     * @param Battle $battle
     * @Security("has_role('ROLE_CREATE_BATTLE')")
     * @Route("/commanderforbattle/{battle}", name="commander_for_battle", requirements={"battle": "\d+"})
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function commanderForBattleAction(Request $request, Battle $battle)
    {
        if (!$battle){
            $battle = new Battle();
        }
        
        $choices = WotReplayUtils::getClanPlayersFromBattleResult($battle->getDataArray());
        $players = $this->getEntityManager()->getRepository('AppBundle:Player')->findAllByAccountIdsArray(array_values($choices));
        foreach ($players as $player)
        {
            $choices[$player->getUsername()] = $player->getId();
        }

        $form = $this->createFormBuilder($battle)
            ->add('commander', ChoiceType::class, ['choices' => $players,
                'label' => 'Select Commander',
                'choice_label' => function($player, $key, $index) {
        /** @var Player $player */
                return strtoupper($player->getUsername()); }
            ] )
            ->add('save', SubmitType::class, array('label' => 'Assign Commander'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->persist($battle);
            $this->getEntityManager()->flush();

            $this->addFlash('success', 'Success creating battle from replay file!');
            return $this->redirectToRoute('battle', ['request' => $request, 'battle' => $battle->getId()]);
        }
        
        return $this->render('cwtracker/commander_for_battle.html.twig', array(
            'form' => $form->createView(),
            'panel' => [
                'title' => 'Assign commander for battle',
                'small_title' => 'choose from a list'
            ],
            'battle' => $battle,
        ));
    }

    /**
     * @param Request $request
     * @param Battle $battle
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/battle/{battle}", name="battle", options={"expose"=true})
     * @Security("has_role('ROLE_UPLOAD_REPLAY')")
     */
    public function viewBattleAction(Request $request, Battle $battle)
    {
        $replay = new Replay();
        $form = $this->createForm(ReplayType::class, $replay);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // ... perform some action, such as saving the task to the database
            $this->getEntityManager()->persist($replay);

            $parser = new WotReplayParser();
            $filename = $replay->getReplayFile()->getRealPath();
            $results = $parser->parse($filename);
            $player = $this->getEntityManager()->getRepository('AppBundle:Player')
                ->findOneByUsername($results['battle']['playerName']);

            if ($results['arena'] != $battle->getArenaId())
            {
                $form->addError(new FormError('This is not a replay for this battle!'));
                // for some reasons the file stays although replay is removed
                unlink($replay->getReplayFile()->getFileInfo(File::class)->getPathname());
                $this->getEntityManager()->remove($replay);
            }
            elseif (null == $player)
            {
                $form->addError(new FormError('Unknown replay player: '.$results['battle']['playerName'].'!'));
                // for some reasons the file stays although replay is removed
                unlink($replay->getReplayFile()->getFileInfo(File::class)->getPathname());
                $this->getEntityManager()->remove($replay);
            }
            elseif (in_array($player->getId(), $battle->getBattleReplayPlayerIds() ))
            {
                $form->addError(new FormError('We already have a replay for player: '.$player->getUsername().'!'));
                // for some reasons the file stays although replay is removed
                unlink($replay->getReplayFile()->getFileInfo(File::class)->getPathname());
                $this->getEntityManager()->remove($replay);
            }
            else
            {
                $replay->setPlayer($player);
                $replay->setBattle($battle);

                $this->getEntityManager()->flush();
                $this->getEntityManager()->refresh($battle);
            }
        }

        return $this->render('cwtracker/battle.html.twig', array(
            'battle' => $battle,
            'panel' => [
                'title' => 'Upload',
                'small_title' => 'additional replay for this battle'
            ],
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param mixed $clan
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/list/{clan}", name="list_clan_battles")
     * @Security("has_role('ROLE_USER')")
     */
    public function listClanBattlesAction(Request $request, $clan)
    {
        $datatable = $this->buildDatatable($clan);

        return $this->render(':cwtracker:list_clan_battles_ajax.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     * @Route("/bulk/delete", name="battle_bulk_delete")
     * @Security("has_role('ROLE_DELETE_BATTLE')")
     */
    public function bulkBattleDeleteAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();
        $clan = '';
        if ($isAjax) {
            $choices = $request->request->get('data');
            $token = $request->request->get('token');
            if (!$this->isCsrfTokenValid('multiselect', $token)) {
                throw new AccessDeniedException('The CSRF token is invalid.');
            }

            $repository = $this->getEntityManager()->getRepository('AppBundle:Battle');
            foreach ($choices as $choice) {
                $entity = $repository->find($choice['value']);
                $clan = $entity->getClan();
                $this->getEntityManager()->remove($entity);
            }
            $this->getEntityManager()->flush();
            return new Response('Success', 200);
        }
        return new Response('Bad Request', 400);
    }


    /**
     *
     * @param mixed $clan
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/list/json/{clan}", name="list_json_clan_battles")
     * @Security("has_role('ROLE_USER')")
     */
    public function listClanBattlesJsonAction($clan)
    {
        $datatable = $this->buildDatatable($clan);

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $query->buildQuery();

        $qb = $query->getQuery();
        $qb->andWhere("battle.clan = '".$clan."'");

        $query->setQuery($qb);

        return $query->getResponse(false);
    }

    /**
     * @param string|integer $clan
     * @return \AppBundle\Datatables\BattleDatatable
     */
    private function buildDatatable(&$clan)
    {
        $clan = $this->getClanTag($clan);
        $ajaxUrl = $this->generateUrl('list_json_clan_battles', ['clan' => $clan]);

        $datatable = $this->get('app.datatables.battle_datatable');
        $datatable->buildDatatable(['ajaxUrl' => $ajaxUrl]);

        return $datatable;
    }

    /**
     * @param mixed $clan
     * @return mixed
     */
    private function getClanTag($clan)
    {
        $clanTag = $clan;
        if (is_numeric($clan))
            $clanTag = $this->getEntityManager()->getRepository('AppBundle:Clan')->findOneById($clan)->getTag();

        return $clanTag;
    }
}
