<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-07-19
 * Time: 21:21
 */

namespace AppBundle\Utils;




use AppBundle\Entity\Clan;
use AppBundle\Entity\Payout;
use AppBundle\Entity\PayoutConfig;
use AppBundle\Entity\PayoutConfigUsedByPayout;
use AppBundle\Entity\Player;
use Doctrine\Common\Persistence\ObjectManager;

class PayoutManipulator
{

    private $em;
    
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function initializePayout(Clan $clan, $goldToPay)
    {
        // 1. create payout entity
        // 2. create config used for payout from default config
        // 3. select battles that are not paid
        // 4. generate payout to player based on default config

        // 1.
        $payout = new Payout();
        $payout->setClan($clan);
        $payout->setTotalGoldToPay($goldToPay);
        $payout->setCreatedAt(new \DateTime());
        $payout->setDescription('Update: payout '.$goldToPay.' of gold, created at: '.date_format(new \DateTime(), 'Y-m-d H:i:s'));

        // 3.
        $payout->setBattles($this->getPayoutBattlesArray($clan));

        // 2.
        $payoutConfig = $this->copyDefPayoutConfig();
        $payoutConfig->setPayout($payout);
        $payout->setConfig($payoutConfig);

        $this->em->persist($payoutConfig);
        $this->em->persist($payout);

        $this->calculatePlayersPayout($payout);

    }

    public function calculatePlayersPayout(Payout $payout)
    {
        $config = $payout->getConfig();

        $cwGold = round($payout->getTotalGoldToPay() * $config->getPercentCw());
        $shGold = round($payout->getTotalGoldToPay() * $config->getPercentSh());
        $hqGold = $payout->getTotalGoldToPay() - $cwGold - $shGold; //avoid rounding problem
        
        $playersParticipation = $this->calculatePlayersCwPayout($cwGold, $payout);
        //$playersParticipation = array_merge_recursive($playersParticipation,
        // $this->calculatePlayersShPayout($shGold, $payout));

        $playersParticipation = $this->array_merge_unique($playersParticipation, $this->calculatePlayersShPayout($shGold, $payout));
        
        return $playersParticipation;
    }
    
    private function calculatePlayersShPayout($shGold, Payout $payout)
    {
        $clan = $payout->getClan()->getTag();
        $config = $payout->getConfig();
        $playersParticipation = [];
        $ttlRegRes = 0;
        $ttlExtraRes = 0;
        $regularShGold = round($shGold * (1 - $config->getPercentExtraShare()));
        $extraShGold = $shGold - $regularShGold;
        
        // 1. select clan members that are having minimum amount of resources earned and below extra level
        $regularShPlayers = $this->em->getRepository('AppBundle:Player')
            ->findAllClanMembersBetweenMinAndExtraResourceEarned(
                $clan,
                $config->getMinResourceToBePaid(), 
                $config->getMinResourceToBeExtraPaid()
            );
        
        /** @var Player $shPlayer */
        foreach ($regularShPlayers as $shPlayer)
        {
            $ttlRegRes += $playersParticipation[$shPlayer->getId()]['resourcesEarned'] = $shPlayer->getResourcesToPayout();
            $playersParticipation[$shPlayer->getId()]['totalResourcesEarned'] = $shPlayer->getTotalResourcesEarned();
        }
        foreach ($regularShPlayers as $shPlayer)
        {
            $playersParticipation[$shPlayer->getId()]['goldFromSh'] = round($regularShGold * $shPlayer->getResourcesToPayout() / $ttlRegRes);
        }

        // 2. select clan members that are having more resources earned than extra level
        $extraShPlayers = $this->em->getRepository('AppBundle:Player')
            ->findAllClanMembersWithExtraResourceEarned($clan, $config->getMinResourceToBeExtraPaid()
            );

        foreach ($extraShPlayers as $shPlayer)
        {
            $ttlExtraRes += $playersParticipation[$shPlayer->getId()]['resourcesEarned'] = $shPlayer->getResourcesToPayout();
            $playersParticipation[$shPlayer->getId()]['totalResourcesEarned'] = $shPlayer->getTotalResourcesEarned();
        }
        foreach ($extraShPlayers as $shPlayer)
        {
            $playersParticipation[$shPlayer->getId()]['goldFromSh'] = round($extraShGold * $shPlayer->getResourcesToPayout() / $ttlExtraRes);
        }

        return $playersParticipation;
    }

    private function array_merge_unique($array1, $array2) {
        foreach($array2 AS $k => $v) {
            if(!isset($array1[$k]))
            {
                $array1[$k] = $v;
            }
            else
            {
                $array1[$k] = $array1[$k] + $v;

            }

        }
        return $array1;
    }
    

    /**
     * @param int $cwGold
     * @param Payout $payout
     * @return array
     */
    private function calculatePlayersCwPayout($cwGold, Payout $payout)
    {
        $playersParticipation = [];
        $totalCwPoints = 0;

        $config = $payout->getConfig();

        foreach ($payout->getBattles() as $btle)
        {
            $battle = $this->em->getRepository('AppBundle:Battle')->find($btle);

            $result = $battle->getResult();
            $commander = $battle->getCommander()->getId();

            foreach ($battle->getBattleAttendances() as $btleAtt)
            {
                // check if player is still in clan
                if ($btleAtt->getPlayer()->getClan() != $payout->getClan()->getTag())
                    continue;

                // create array
                $playerId = $btleAtt->getPlayer()->getId();
                $playersParticipation[$playerId] = $playersParticipation[$playerId] ?? array (
                        'noCommandedWin' => 0,
                        'noCommandedDraw' => 0,
                        'noCommandedLost' => 0,
                        'noPlayedWin' => 0,
                        'noPlayedDraw' => 0,
                        'noPlayedLost' => 0,
                        'ptsFromCw' => 0,
                    );
                if($playerId == $commander)
                {
                    switch ($result){
                        case 1:     // win
                            $playersParticipation[$playerId]['noCommandedWin'] += 1;
                            $totalCwPoints += $playersParticipation[$playerId]['ptsFromCw'] += $config->getPtsCommanderWin();
                            break;
                        case 0:     // draw
                            $playersParticipation[$playerId]['noCommandedDraw'] += 1;
                            $totalCwPoints += $playersParticipation[$playerId]['ptsFromCw'] += $config->getPtsCommanderDraw();
                            break;
                        case -1:    // lost
                            $playersParticipation[$playerId]['noCommandedLost'] += 1;
                            $totalCwPoints += $playersParticipation[$playerId]['ptsFromCw'] += $config->getPtsCommanderLost();
                    }
                }
                else
                {
                    switch ($result){
                        case 1:     // win
                            $playersParticipation[$playerId]['noPlayedWin'] += 1;
                            $totalCwPoints += $playersParticipation[$playerId]['ptsFromCw'] += $config->getPtsPlayerWin();
                            break;
                        case 0:     // draw
                            $playersParticipation[$playerId]['noPlayedDraw'] += 1;
                            $totalCwPoints += $playersParticipation[$playerId]['ptsFromCw'] += $config->getPtsPlayerDraw();
                            break;
                        case -1:    // lost
                            $playersParticipation[$playerId]['noPlayedLost'] += 1;
                            $totalCwPoints += $playersParticipation[$playerId]['ptsFromCw'] += $config->getPtsPlayerLost();
                    }
                }

            }
        }

        foreach ($playersParticipation as $key => $value)
        {
            $playersParticipation[$key]['goldFromCw'] = round( $cwGold * $value['ptsFromCw'] / $totalCwPoints );
        }
        
        return $playersParticipation;
    }

    /**
     * @param Clan $clan
     * @return array
     */
    private function getPayoutBattlesArray(Clan $clan)
    {
        $battles = $this->em->getRepository('AppBundle:Battle')->findBy(array(
           'clan' => $clan->getTag(),
           'isGoldPayed' => false,
           'stronghold' => false,
        ));

        $battlesArr = [];
        foreach ($battles as $battle)
        {
            $battlesArr[] = $battle->getId();
        }

        return $battlesArr;
    }

    /**
     * @return PayoutConfigUsedByPayout
     */
    private function copyDefPayoutConfig()
    {
        $defConfig = $this->em->getRepository('AppBundle:PayoutConfig')->find(1);
        $payoutConfig = new PayoutConfigUsedByPayout();

        $payoutConfig->setMinResourceToBeExtraPaid($defConfig->getMinResourceToBeExtraPaid());
        $payoutConfig->setMinResourceToBePaid($defConfig->getMinResourceToBePaid());
        $payoutConfig->setPercentCw($defConfig->getPercentCw());
        $payoutConfig->setPercentExtraShare($defConfig->getPercentExtraShare());
        $payoutConfig->setPercentHqBonus($defConfig->getPercentHqBonus());
        $payoutConfig->setPercentSh($defConfig->getPercentSh());
        $payoutConfig->setPtsCommanderDraw($defConfig->getPtsCommanderDraw());
        $payoutConfig->setPtsCommanderLost($defConfig->getPtsCommanderLost());
        $payoutConfig->setPtsCommanderWin($defConfig->getPtsCommanderWin());
        $payoutConfig->setPtsPlayerDraw($defConfig->getPtsPlayerDraw());
        $payoutConfig->setPtsPlayerLost($defConfig->getPtsPlayerLost());
        $payoutConfig->setPtsPlayerWin($defConfig->getPtsPlayerWin());
        $payoutConfig->setRecruitFactor($defConfig->getRecruitFactor());
        $payoutConfig->setReservistFactor($defConfig->getReservistFactor());

        return $payoutConfig;
    }
}