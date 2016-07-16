<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-04-24
 * Time: 14:08
 */

namespace AppBundle\Wot;


/**
 * Class WotApiWgnClans
 * @package AppBundle\Wot implements api calls to WGN/Clans subsection of API
 */
class WotApiWgnClans extends WotApiCall
{
    /**
     * WotApiWgnClans constructor.
     */
    public function __construct()
    {
        parent::__construct( func_get_args() );
        $this->_api_url .= '/wgn/clans/';
    }


    /**
     * @param $call_params array
     *       fields 	string, array
     *       Response field. The fields are separated with commas. Embedded fields are separated with dots. To exclude a field, use “-” in front of its name. In case the parameter is not defined, the method returns all fields.
     *
     *       search 	string required
     *       Part of name or tag for clan search. Minimum 2 characters
     *
     *       limit 	numeric
     *       Number of returned entries (fewer can be returned, but not more than 100). If the limit sent exceeds 100, an limit of 100 is applied (by default).
     *
     *       page_no 	numeric
     *       Page number
     * @link http://eu.wargaming.net/developers/api_reference/wgn/clans/list/
     * @return array
     */
    public function getClans($call_params)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'list/?';
        return $this->getData();
    }

    /**
     * @param $call_params array may include
     * fields 	string, list Response field. The fields are separated with commas. Embedded fields are separated with dots. To exclude a field, use “-” in front of its name. In case the parameter is not defined, the method returns all fields.
     *
     * access_token string  Access token is used to access personal user data. The token is obtained via authentication and has expiration time.
     *
     * extra 	string, list Extra fields to be included into the response. Valid values:
     *      private.online_members
     *
     * clan_id 	numeric, list, required Clan ID
     *
     * members_key 	string This parameter changes members field type. Valid values:
     *      "id" — Members field will contain associative array with account_id indexing in response
     *
     * @link http://eu.wargaming.net/developers/api_reference/wgn/clans/info/
     * @return array
     */
    public function getClanDetails($call_params)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'info/?';
        return $this->getData();
    }


    /**
     * @param $call_params array may include
     * fields    string, list
     * Response field. The fields are separated with commas. Embedded fields are separated with dots. To exclude a field, use “-” in front of its name. In case the parameter is not defined, the method returns all fields.
     * *account_id    numeric, list Required
     * Account ID
     * @link https://eu.wargaming.net/developers/api_reference/wgn/clans/membersinfo/
     * @return array
     */
    public function getClanMemberDetails($call_params)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'membersinfo/?';
        return $this->getData();
    }
}