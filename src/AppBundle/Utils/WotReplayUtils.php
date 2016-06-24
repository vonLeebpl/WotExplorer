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
     * @return int
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

    public static function guessBattleEnemyClan(array &$result)
    {
        $clan = self::guessBattleClan($result);
        if ($clan != 'UNKNOWN')
        {
            foreach ($result['players'] as $player)
            {
                if ($player['clanAbbrev'] != $clan)
                    return $player['clanAbbrev'];
            }
        }

        return 'UNKNOWN';
    }

    public static function getClanPlayersFromBattleResult(array $result)
    {
        $clan = self::guessBattleClan($result);
        $ret = [];
        if ($clan != 'UNKNOWN') {
            foreach ($result['players'] as $player)
            {
                if ($player['clanAbbrev'] == $clan)
                    $ret[] = $player['name'];
            }
        }
        return $ret;
    }
}