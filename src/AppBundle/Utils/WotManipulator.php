<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-23
 * Time: 22:31
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Battle;
use AppBundle\Entity\BattleAttendance;
use AppBundle\Entity\BattleEnemyAttendance;
use AppBundle\Entity\Clan;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventAccountData;
use AppBundle\Entity\Map;
use AppBundle\Entity\Player;
use AppBundle\Entity\RefreshMonitor;
use AppBundle\Entity\Replay;
use AppBundle\Entity\Tank;
use AppBundle\Wot\WotApiWrapper;
use Doctrine\Orm\EntityManager;

class WotManipulator
{
    private $entityManager;
    private $wot_manipulator;
    private $wrapper;
    private static $tanks = [];


    public function __construct(EntityManager $entity_manager, array $wot_manipulator)
    {
        $this->entityManager = $entity_manager;
        $this->wot_manipulator = $wot_manipulator;
        $this->wrapper = new WotApiWrapper();
    }

    /**
     * @param array $results
     * @param Player $currentUser
     * @param Replay $replay
     * @return Battle
     */
    public function createBattleFromReplay(array $results, Player $currentUser, Replay $replay)
    {
        $battle = new Battle();
        $battle->setArenaId($results['arena']);
        $battle->setDataArray($results);
        $battle->setDatePlayed(new \DateTime($results['battle']['dateTime']));
        $battle->setMapId($results['battle']['mapName']);

        /**
         * @var Map $map
         */
        $map = $this->entityManager->getRepository('AppBundle:Map')
            ->findOneByArenaId($results['battle']['mapName']);

        $battle->setMapName($map->getName()??'Unknown');
        $battle->setCreatorId($currentUser->getId());
        // TODO: below can be improved to one function returning array
        $battle->setClan(WotReplayUtils::guessBattleClan($results));
        $battle->setEnemyClan(WotReplayUtils::guessBattleEnemyClan($results));
        $battle->setResult(WotReplayUtils::guessBattleResult($results));
        $battle->setStronghold(WotReplayUtils::guessBattleStronghold($results));
        $battle->setCreatedAt(new \DateTime());

        $replay->setBattle($battle);
        //$replay->setPlayerName($results['battle']['playerName']);

        $player = $this->entityManager->getRepository('AppBundle:Player')
            ->findOneByUsername($results['battle']['playerName']);

        if (is_object($player)){
            $replay->setPlayer($player);
        }
        $this->entityManager->persist($battle);
        $this->createBattleAttendanceFromReplay($results, $battle);

        $this->entityManager->flush();

        return $battle;
    }

    /**
     * @param array $results
     * @param Battle $battle
     */
    public function createBattleAttendanceFromReplay(array $results, Battle $battle)
    {
        //persist clan data results
        $clanDataResult = $this->extractClanDataFromResults($results);
        $clanScore = 0;
        $enemyClanScore = 0;
        foreach ($clanDataResult as $name => $performance)
        {
            // check if player is in our db, if not create him
            if (null === $player = $this->entityManager->getRepository('AppBundle:Player')->findOneByUsername($name))
            {
                //old player from old replay :)
                $player = new Player();
                $player->setAccountId($performance['account_id']);
                $player->setUsername($name);
                $this->entityManager->persist($player);
                $this->entityManager->flush($player);
            }
            $btlAtt = new BattleAttendance();
            $btlAtt->setTankId($performance['tank_id']);
            $btlAtt->setBattle($battle);
            $btlAtt->setDamage($performance['damage']);
            $btlAtt->setFrags($performance['frags']);
            $btlAtt->setIsAlive($performance['is_alive']);
            $btlAtt->setOrygXp($performance['oryg_xp']);
            $btlAtt->setPlayer($player);
            $btlAtt->setTankShortName($this->getTankShortNameFromTankId($performance['tank_id'])??('Unknown: '.$performance['tank_id']));

            $this->entityManager->persist($btlAtt);
            $clanScore += $performance['frags'];
        }
        
        // persist enemy clan data results
        $enemyClanDataResult = $this->extractEnemyClanDataFromResults($results);
        foreach ($enemyClanDataResult as $name => $performance)
        {
            $btlAtt = new BattleEnemyAttendance();
            //$btlAtt->setTankId($performance['tank_id']);
            $btlAtt->setBattle($battle);
            $btlAtt->setDamage($performance['damage']);
            $btlAtt->setFrags($performance['frags']);
            $btlAtt->setIsAlive($performance['is_alive']);
            $btlAtt->setOrygXp($performance['oryg_xp']);
            $btlAtt->setName($performance['name']);
            $btlAtt->setTankShortName($this->getTankShortNameFromTankId($performance['tank_id']));

            $this->entityManager->persist($btlAtt);
            $enemyClanScore += $performance['frags'];
        }

        $battle->setScore($clanScore.'-'.$enemyClanScore);
    }

    /**
     * @param int $tankId
     * @return string
     */
    public function getTankShortNameFromTankId($tankId)
    {
        if (false === $shortName = array_search($tankId, $this::$tanks))
        {
            $shortName = $this->entityManager->getRepository('AppBundle:Tank')->findOneByTankId($tankId)->getShortName();
            $this::$tanks[$shortName] = $tankId;
        }
        return $shortName;
    }

    /**
     * returns clan data results of battle
     * @param array $results
     * @return array
     */
    private function extractClanDataFromResults(array $results)
    {
        $clanPlayers = WotReplayUtils::getClanPlayersFromBattleResult($results);
        $clanResults = [];
        foreach ($results['vehicles'] as $vehicle)
        {
            if(array_key_exists($vehicle['name'], $clanPlayers))
            {
                $clanResults[$vehicle['name']] = [
                    'is_alive'   => $vehicle['isAlive'],
                    'damage'    => $vehicle[0]['damageDealt'],
                    'oryg_xp'    => $vehicle[0]['xp'],
                    'tank_id'   => $vehicle[0]['typeCompDescr'],
                    'frags'     => $vehicle[0]['kills'],
                    'account_id' => $vehicle[0]['accountDBID'],
                    'resource_absorbed' => $vehicle[0]['resourceAbsorbed'] + $vehicle[0]['fortResource'],
                ];
            }
        }
        return $clanResults;
    }

    /**
     * returns enemy clan battle results from replay
     * @param array $results
     * @return array
     */
    public function extractEnemyClanDataFromResults(array $results)
    {
        $clanPlayers = WotReplayUtils::getEnemyClanPlayersFromBattleResult($results);
        $clanResults = [];
        foreach ($results['vehicles'] as $vehicle)
        {
            if(array_key_exists($vehicle['name'], $clanPlayers))
            {
                $clanResults[$vehicle['name']] = [
                    'is_alive'   => $vehicle['isAlive'],
                    'damage'    => $vehicle[0]['damageDealt'],
                    'oryg_xp'    => $vehicle[0]['xp'],
                    'tank_id'   => $vehicle[0]['typeCompDescr'],
                    'frags'     => $vehicle[0]['kills'],
                    'name'      => $vehicle['name'],
                ];
            }
        }
        return $clanResults;
    }

    /**
     * @param bool $force
     */
    public function refreshClans($force = false)
    {
        $clansToRefresh = $this->wot_manipulator['security_settings']['authorised_clans'];
        // TODO: check if it's a need to refresh using updated_at and LastRefreshedAt
        foreach ($clansToRefresh as $refreshedClan)
        {
            //1. get clan_id
            if (!$clan = $this->entityManager->getRepository('AppBundle:Clan')->findOneByTag($refreshedClan)) {
                $clan = new Clan();
                $clanId = $this->wrapper->searchClan($refreshedClan, null, 1)[0]['clan_id'];
            }
            else
                $clanId = $clan->getClanId();

            $clanData = $this->wrapper->getClanDetails($clanId)[$clanId];
            $clan = $clan->hydrateFromArray($clanData, $this->entityManager);
            
            $this->entityManager->persist($clan);
            $this->entityManager->flush($clan);
            
            $this->refreshClanMembers($clan);
            $this->refreshClanStrongholdStats($clan);
            $clan->setLastRefreshedAt(new \DateTime());
        }
        $this->entityManager->flush();
    }

    /**
     * @param Clan $clan
     */
    public function refreshClanMembers(Clan $clan)
    {
        $clanMembers = $clan->getMembers();
        $emblem = $clan->getEmblems()['x64']['portal'];

        foreach ($clanMembers as $clanMember)
        {
            if(null === $player = $this->entityManager->getRepository('AppBundle:Player')->findOneByAccountId($clanMember['account_id']))
            {
                $player = new Player();
            }
            $player->setUsername($clanMember['account_name']);
            $player->setClan($clan->getTag());
            $player->setAccountId($clanMember['account_id']);
            $player->setEnabled(true);
            $player->setEmblem($emblem);
            $player->setPosition($clanMember['role_i18n']);

            //clean previous roles
            $player->setRoles(array());

            //set new roles 
            foreach ($this->wot_manipulator['security_settings']['roles_matrix'] as $key => $value)
             {
                if (in_array($clanMember['role'], $value) ){
                    $player->addRole($key);
                }
            }
            if (in_array($clanMember['role'], $this->wot_manipulator['security_settings']['hq_member_roles']))
                $player->setIsHqMember(true);
            else
                $player->setIsHqMember(false);

            if (in_array($clanMember['account_name'], $this->wot_manipulator['security_settings']['super_admins'])) {
                $player->setSuperAdmin(true);
            }

            $this->entityManager->persist($player);
        }
        // find all existing clan members in Players and remove clan tag from orphaned members
        $oldMemberIds = [];
        $oldClanMembers = $this->entityManager->getRepository('AppBundle:Player')->findBy(array('clan' => $clan->getTag()));
        foreach ($oldClanMembers as $oldClanMember)
        {
            $oldMemberIds[] = $oldClanMember->getAccountId();
        }
        $clanMemberIds = $clan->getMembersIds();
        $idsToRemove = array_diff($oldMemberIds, $clanMemberIds);
        foreach ($idsToRemove as $item)
        {
            /**
             * @var Player $player
             */
            $player = $this->entityManager->getRepository('AppBundle:Player')->findOneByAccountId($item);
            $player->setEnabled(false);
            $player->setClan(null);
        }
    }

    /**
     * @param Clan $clan
     */
    public function refreshClanStrongholdStats(Clan $clan)
    {
        $clanMembersIds = $clan->getMembersIds();
        $shStats = $this->wrapper->getPlayerStrongholdStats($clanMembersIds);
        
        foreach ($shStats as $stat)
        {
            /**
             * @var Player $player
             */
            if (null !== $player = $this->entityManager->getRepository('AppBundle:Player')->findOneByAccountId($stat['account_id']))
            {
                $player->setTotalResourcesEarned($stat['total_resources_earned']);
                $player->setWeekResourcesEarned($stat['week_resources_earned']);
            }
        }
    }
    /**
     * Refreshes maps in db from WOT API
     */
    public function refreshWotMaps()
    {
        $data = $this->wrapper->getMaps();

        $rep = $this->entityManager->getRepository('AppBundle:Map');

        foreach ($data as $item)
        {
            $map = $rep->findOneByArenaId($item['arena_id']);
            if (!$map)
            {
                $map = new Map();
                $map->setArenaId($item['arena_id']);
                $map->setName($item['name_i18n']);
                $map->setCamo($item['camouflage_type']);
                $map->setDescription($item['description']);

                $this->entityManager->persist($map);
            }
        }
        $rep = $this->entityManager->getRepository('AppBundle:RefreshMonitor');

        /**
         * @var RefreshMonitor $info
         */
        $info = $rep->findOneById(1);
        if (!$info){
            $info = new RefreshMonitor();
            $this->entityManager->persist($info);
        }
        $info->setLastMapRefreshAt(new \DateTime());
        $this->entityManager->flush();
    }

    /**
     * Refreshes tanks in db from WOT API
     */
    public function refreshWotTanks()
    {
        // check if we need to refresh at all
        $data = $this->wrapper->getTankopediaInfo();
        $wotLastTankRefreshAt = new \DateTime('@'.$data['tanks_updated_at']);
        
        $rep = $this->entityManager->getRepository('AppBundle:RefreshMonitor');

        /**
         * @var RefreshMonitor $info
         */
        $info = $rep->findOneById(1);

        if ($info)
        {
            if (!null === $info->getLastTankRefreshAt() and $info->getLastTankRefreshAt() >= $wotLastTankRefreshAt)
                return;
        }
        else {
            $info = new RefreshMonitor();
            $this->entityManager->persist($info);
        }
        
        // now we need to refresh tanks
        $rep = $this->entityManager->getRepository('AppBundle:Tank');
        $data = $this->wrapper->getVehicles();

        foreach ($data as $vehicle)
        {
            //$compDescr = $this->calcCompDescr($vehicle);
            /**
             * @var Tank $tank
             */
            $tank = $rep->findOneByTankId($vehicle['tank_id']);
            if (!$tank) {
                $tank = new Tank();
            }

            $tank->setImages($vehicle['images']);
            $tank->setIsPremium($vehicle['is_premium']);
            $tank->setName($vehicle['name']);
            $tank->setNation($vehicle['nation']);
            $tank->setPriceCredit($vehicle['price_credit']);
            $tank->setPriceGold($vehicle['price_gold']);
            $tank->setShortName($vehicle['short_name']);
            $tank->setTag($vehicle['tag']);
            $tank->setTankId($vehicle['tank_id']);
            $tank->setTier($vehicle['tier']);
            $tank->setType($vehicle['type']);

            $this->entityManager->persist($tank);
        }

        $info->setLastTankRefreshAt(new \DateTime());

        $this->entityManager->flush();
    }

    /**
     * A method that uses deprecated method for tanks API refresh
     */
    public function refreshWotOldTanks()
    {
        $rep = $this->entityManager->getRepository('AppBundle:Tank');
        $data = $this->wrapper->getVehicles();
        
        foreach ($data as $vehicle)
        {
            //$compDescr = $this->calcCompDescr($vehicle);
            /**
             * @var Tank $tank
             */
            $tank = $rep->findOneByTankId($vehicle['tank_id']);
            if (!$tank) {
                $tank = new Tank();

                $tank->setIsPremium($vehicle['is_premium']);
                $tank->setName($vehicle['name_i18n']);
                $tank->setNation($vehicle['nation']);
                $tank->setShortName($vehicle['short_name_i18n']);
                $tank->setTag($vehicle['name']);
                $tank->setTankId($vehicle['tank_id']);
                $tank->setTier($vehicle['level']);
                $tank->setType($vehicle['type']);

                $this->entityManager->persist($tank);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * Refreshes events in db from WOT API
     */
    public function refreshEvents()
    {
        $rep = $this->entityManager->getRepository('AppBundle:Event');
        $data = $this->wrapper->getEvents();
        
        foreach ($data as $event)
        {
            $e = $rep->findOneById($event['event_id']);
            if (!$e) $e = new Event();

            $e->parseFromArray($event);
            $this->entityManager->persist($e);
        }

        $this->entityManager->flush();
    }

    public function refreshClanEventData(Clan $clan, Event $event)
    {
        // delete old data
        $rep = $this->entityManager->getRepository('AppBundle:EventAccountData');
        $rep->deleteClanEventData($clan->getId(), $event->getId());

        $event_id = $event->getId();
        $front_id = $event->getFronts()[0];
        
        foreach ($clan->getMembersIds() as $account_id)
        {
            //call WOT API
            $accountName = $this->entityManager->getRepository('AppBundle:Player')->findOneByAccountId($account_id)->getUsername();
            $data = $this->wrapper->getEventAccountInfo($account_id, $event_id, $front_id);
            //var_dump($data[$account_id]['events'][$event_id]);
            $eai = new EventAccountData();
            $eai->setAccountName($accountName);
            $eai->setClan($clan);
            $eai->parseFromArray($data[$account_id]['events'][$event_id]);
            
            $this->entityManager->persist($eai);
        }
        
        $this->entityManager->flush();
    }

    private function calcCompDescr(&$vehicle)
    {
        $nation_ids = $this->wot_manipulator['tank_nations'];
        $nationId = 0;
        while (true) {
            if ($vehicle['nation'] == current($nation_ids))
            {
                $nationId = key($nation_ids);
                break;
            }
            next($nation_ids);
        }

        return 1 + ($nationId << 4) + ($vehicle['tank_id'] << 8);
    }
}