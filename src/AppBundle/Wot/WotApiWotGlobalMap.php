<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-04-24
 * Time: 12:56
 */

namespace AppBundle\Wot;


class WotApiWotGlobalMap extends WotApiCall
{
    /**
     * WotApiWotGlobalMap constructor.
     */
    public function __construct()
    {
        parent::__construct( func_get_args() );
        $this->_api_url .= '/wot/globalmap/';
    }


    /**
     * @param array $call_params
     *can be:
        fields 	string, array
        page_no 	numeric
        event_id 	string
        limit 	numeric
        status 	string 'FINISHED | ACTIVE | PLANNED'
     * @link http://eu.wargaming.net/developers/api_reference/wot/globalmap/events/
     * @return array
     *
     */
    public function getEvents($call_params = null)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'events/?';
        return $this->getData();
    }

    /**
     * @param $call_params
     *       fields 	string, list
     *       Response field. The fields are separated with commas. Embedded fields are separated with dots. To exclude a field, use “-” in front of its name. In case the parameter is not defined, the method returns all fields.
     *
     *       *event_id 	string, required
     *        Event ID
     *
     *        *front_id 	string, list, required
     *        Front ID. Max limit is 10.
     *
     *       *account_id 	numeric, required
     *        Account ID
     * @link http://eu.wargaming.net/developers/api_reference/wot/globalmap/eventaccountinfo/
     * Method returns player's statistics for a specific event
     * @return array
     */
    public function getEventAccountInfo($call_params)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'eventaccountinfo/?';
        return $this->getData();
    }
}