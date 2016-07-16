<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-24
 * Time: 18:08
 */

namespace AppBundle\Utils;


class WotReplayUtils
{
    /**
     * @param array $result
     * @return int 0 = draw, 1 = win, -1 = lost
     */
    public static function guessBattleResult(array &$result)
    {
        $winner_team = $result['battle']['winnerTeam'];
        $player_team = $result['player']['team'];

        if ($winner_team == 0){
            return 0;
        }
        elseif ($winner_team == $player_team){
            return 1;
        }

        return -1;
    }

    /**
     * @param array &$result
     * @return boolean
     */
    public static function guessBattleStronghold( array &$result )
    {
        if (in_array($result['battle']['battleType'], [10, 11]))
            return true;
        else
            return false;
    }

    /**
     * @param array $result
     * @return mixed
     */
    public static function guessBattleClan( array &$result)
    {
        $playerName = $result['battle']['playerName'];
        foreach ($result['players'] as $player)
        {
            if ($player['name'] == $playerName)
                return $player['clanAbbrev'];
        }

        return 'UNKNOWN';
    }

    /**
     * @param array $result
     * @return string
     */
    public static function guessBattleEnemyClan(array &$result)
    {
        $clan = self::guessBattleClan($result);
        $player_team = $result['player']['team'];
        if ($clan != 'UNKNOWN')
        {
            foreach ($result['players'] as $player)
            {
                if ($player['clanAbbrev'] != $clan and $player['team'] != $player_team)
                    return $player['clanAbbrev'];
            }
        }

        return 'UNKNOWN';
    }

    /**
     * @param array $result
     * @return array ['name' => 'account_id']
     */
    public static function getClanPlayersFromBattleResult(array $result)
    {
        $player_team = $result['player']['team'];
        $ret = [];

        foreach ($result['players'] as $key => $player)
        {
            if ($player['team'] == $player_team)
                $ret[$player['name']] = $key;
        }

        return $ret;
    }

    /**
     * @param array $result
     * @return array
     */
    public static function getEnemyClanPlayersFromBattleResult(array $result)
    {
        $player_team = $result['player']['team'];
        $ret = [];

        foreach ($result['players'] as $key => $player)
        {
            if ($player['team'] != $player_team)
                $ret[$player['name']] = $key;
        }

        return $ret;
    }
}