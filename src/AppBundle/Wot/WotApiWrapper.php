<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-05-06
 * Time: 23:04
 */

namespace AppBundle\Wot;

/**
 * Class WotApiWrapper
 * @package AppBundle\Wot
 *          Wraps WOT API calls
 */
class WotApiWrapper
{
    protected $_call_schemas = [
        'search' => ['search', 'page', 'limit', 'fields'],
        'player_personal_data' => ['account_id', 'fields', 'access_token', 'extra'],
        'clan_member_details' => ['account_id', 'fields'],
        'general' => ['fields'],
        'vehicles' => ['fields', 'tank_id', 'nation', 'type', 'tier'],
        'clan_details' => ['clan_id', 'fields', 'access_token', 'extra', 'members_key'],
        'stronghold_stats' => ['account_id', 'fields', 'access_token'],
    ];

    /**
     * @param array|int $account_id
     * @param null|array|string $fields
     * @param null|string $access_token
     * @return array
     */
    public function getPlayerStrongholdStats($account_id, $fields = null, $access_token = null)
    {
        $call_params = $this->buildCallParams('stronghold_stats', $account_id, $fields, $access_token);
        $call = new WotApiWotStronghold();

        return $call->getPlayerStrongholdStats($call_params);
    }

    /**
     * @param int|array $clanId
     * @param string|array|null $fields
     * @param string|array|null $access_token
     * @param string|array|null $extra
     * @param "id"|null $members_key
     * @return array
     */
    public function getClanDetails($clanId, $fields = null, $access_token = null, $extra = null, $members_key = null)
    {
        $call_params = $this->buildCallParams('clan_details', $clanId, $fields, $access_token, $extra, $members_key);
        $call = new WotApiWgnClans();

        return $call->getClanDetails($call_params);
    }

    /**
     * @param array|string|null $fields
     * @param array|integer|null $tankId
     * @param array|string|null $nation
     * @param string|null $type
     * @param array|integer|null $tier
     * @return array
     */
    public function getVehicles($fields = null, $tankId = null, $nation = null, $type = null, $tier = null)
    {
        $call_params = $this->buildCallParams('vehicles', $fields, $tankId, $nation, $type, $tier);
        $call = new WotApiWotTankopedia();

        return $call->getVehicles($call_params);
    }

    /**
     * @param array|string $fields
     * @return array
     */
    public function getMaps($fields = null)
    {
        $call_params = $this->buildCallParams('general', $fields);
        $call = new WotApiWotTankopedia();
        
        return $call->getMaps($call_params);
    }

    /**
     * @param array|string $fields
     * @return array
     */
    public function getListOfVehicles($fields = null)
    {
        $call_params = $this->buildCallParams('general', $fields);
        $call = new WotApiWotTankopedia();

        return $call->getListOfVehicles($call_params);
    }

    /**
     * @param array|string $fields
     * @return array
     */
    public function getTankopediaInfo($fields = null)
    {
        $call_params = $this->buildCallParams('general', $fields);
        $call = new WotApiWotTankopedia();

        return $call->getTankopediaInfo($call_params);
    }
    
    /**
     * @param int|array $accountId
     * @param string|array $fields
     * @return array
     */
    public function getClanMemberDetails($accountId, $fields = null)
    {
        $call_params = $this->buildCallParams('clan_member_details', $accountId, $fields);
        $call = new WotApiWgnClans();

        return $call->getClanMemberDetails($call_params);
    }

    /**
     * @param $search string tag required
     * @param int $page required if returned list of clans longer than 100
     * @param string|array $fields fields to return
     * @param int $limit default 100 - WOT restriction, can be lower
     *
     * @return array
     */
    public function searchClan( $search, $page = 1, $limit = 100, $fields = null )
    {
        $call_params = $this->buildCallParams('search', $search, $page, $limit, $fields);
        $call = new WotApiWgnClans();

        return $call->getClans($call_params);
    }


    /**
     * @param int|array $accountId
     * @param string|array $fields
     * @param string $accessToken
     * @param string|array $extra
     * @return array
     */
    public function getPlayerPersonalData($accountId, $fields = null, $accessToken = null, $extra = null)
    {
        $call_params = $this->buildCallParams('player_personal_data', $accountId, $fields, $accessToken, $extra);
        $call = new WotApiWotAccounts();

        return $call->getPlayerPersonalData($call_params);
    }

    /**
     * @param $call_schema
     * @param array ...$args
     * @return array
     */
    private function buildCallParams($call_schema, ...$args)
    {
        $params = $this->_call_schemas[$call_schema];
        $ret = array();
        $i = 0;

        foreach($params as $param)
        {
            if (isset($args[$i])) {
                $ret[$param] = $args[$i];
            }
            $i++;
        }

        return $ret;
    }
}