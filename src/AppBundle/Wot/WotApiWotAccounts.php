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
class WotApiWotAccounts extends WotApiCall
{
    /**
     * WotApiWotAccounts constructor.
     */
    public function __construct()
    {
        parent::__construct( func_get_args() );
        $this->_api_url .= '/wot/account/';
    }


    /**
     * @param $call_params array
     *       fields 	string, array
     *       Response field. The fields are separated with commas. Embedded fields are separated with dots. To exclude a field, use “-” in front of its name. In case the parameter is not defined, the method returns all fields.
     *
     *       extra 	string, list
     *    Extra fields to be included into the response. Valid values:
     *
     *      private.boosters
     *      private.garage
     *      private.grouped_contacts
     *      private.personal_missions
     *      private.rented
     *      statistics.fallout
     *      statistics.globalmap_absolute
     *      statistics.globalmap_champion
     *      statistics.globalmap_middle
     *      statistics.random
     *
     *        access_token 	string
     *        Access token is used to access personal user data. The token is obtained via authentication and has expiration time.
     *
     *       *account_id 	numeric, list Required Player ID
     *
     *        language 	string
     *          Localization language. Valid values:
     *
     * @link https://eu.wargaming.net/developers/api_reference/wot/account/info/
     * @return array
     */
    public function getPlayerPersonalData($call_params)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'info/?';
        return $this->getData();
    }

}