<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Battle;
use AppBundle\Entity\Map;
use AppBundle\Entity\Replay;
use AppBundle\Form\Type\ReplayType;
use AppBundle\Utils\WotReplayUtils;
use AppBundle\Wot\WoTReplayParser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CwTrackerController extends BaseController
{
    /**
     * @Route("/cwtracker/newbattlefromreplay", name="new_battle_from_replay")
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
            
            $parser = new WoTReplayParser();
            $filename = $replay->getReplayFile()->getRealPath();
            $results = $parser->parse($filename);

            //check if we have already such a battle
            $arena = $this->getDoctrine()->getRepository('AppBundle:Battle')
                    ->findOneByArenaId($results['arena']);

            if($arena) {
                $form->addError(new FormError('This battle already exists in database!'));
            }
            else {
                $battle = new Battle();
                $battle->setArenaId($results['arena']);
                $battle->setDataArray($results);
                $battle->setDatePlayed(new \DateTime($results['battle']['dateTime']));
                $battle->setMapId($results['battle']['mapName']);

                /**
                 * @var Map $map
                 */
                $map = $this->getDoctrine()->getRepository('AppBundle:Map')
                    ->findOneByArenaId($results['battle']['mapName']);

                $battle->setMapName($map->getName());
                $battle->setCreatorId($this->getUser()->getId());
                $battle->setClan(WotReplayUtils::guessBattleClan($results));
                $battle->setEnemyClan(WotReplayUtils::guessBattleEnemyClan($results));
                $battle->setResult(WotReplayUtils::guessBattleResult($results));
                $battle->setCreatedAt(new \DateTime());

                $replay->setBattle($battle);
                $replay->setPlayerName($results['battle']['playerName']);

                $player = $this->getDoctrine()->getRepository('AppBundle:Player')
                    ->findOneByUsername($results['battle']['playerName']);

                if (is_object($player)){
                    $replay->setPlayer($player);
                }

                $this->getEntityManager()->persist($battle);
                $this->getEntityManager()->flush();

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
     * @param Battle|null $battle
     * @Route("/cwtracker/commanderforbattle/{battle}", name="commander_for_battle")
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function commanderForBattle(Request $request, Battle $battle = null)
    {
        if (!$battle){
            $battle = new Battle();
        }
        
        $choices = WotReplayUtils::getClanPlayersFromBattleResult($battle->getDataArray());
        $form = $this->createFormBuilder($battle)
            ->add('commanderId', ChoiceType::class, ['choices' => $choices,
                'label' => 'Select Commander',
                'choice_label' => function ($value, $key, $index) { return $value;}
            ] )
            ->add('save', SubmitType::class, array('label' => 'Assign Commander'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database

            return 'OK!'; //$this->redirectToRoute('task_success');
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
}
