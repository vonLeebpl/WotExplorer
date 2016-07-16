<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-23
 * Time: 22:09
 */

namespace AppBundle\Wot;


class WotApiWotTankopedia extends WotApiCall
{
    /**
     * WotApiWotTankopedia constructor.
     */
    public function __construct()
    {
        parent::__construct( func_get_args() );
        $this->_api_url .= '/wot/encyclopedia/';
    }

    /**
     * @param array $call_params
     * fields 	string|array
     *    Response field. The fields are separated with commas. Embedded fields are separated with dots. To exclude a field, use “-” in front of its name. In case the parameter is not defined, the method returns all fields.
     * language 	string
     *    Localization language. Valid values:
     * tank_id 	numeric, list Vehicle ID
     * nation 	string, list    Nation
     * type 	string Vehicle type. Valid values:
     *                  "heavyTank" — Heavy Tank
     *                  "AT-SPG" — Tank Destroyer
     *                  "mediumTank" — Medium Tank
     *                  "lightTank" — Light Tank
     *                  "SPG" — SPG
     * tier 	numeric, list    Tier
     * 
     * @return array
     */
    public function getVehicles($call_params)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'vehicles/?';
        return $this->getData();
    }

    /**
     * Return list of vehicles, deprecated soon
     * @param array $call_params
     * fields 	string|array
     *    Response field. The fields are separated with commas. Embedded fields are separated with dots. To exclude a field, use “-” in front of its name. In case the parameter is not defined, the method returns all fields.
     * language 	string
     *    Localization language. Valid values:
     *
     * @return array
     * 
     * http://eu.wargaming.net/developers/api_reference/wot/encyclopedia/tanks/
     */
    public function getListOfVehicles($call_params)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'tanks/?';
        return $this->getData();
    }

    /**
     * @param array $call_params
     * fields 	string|array
     *    Response field. The fields are separated with commas. Embedded fields are separated with dots. To exclude a field, use “-” in front of its name. In case the parameter is not defined, the method returns all fields.
     * language 	string
     *    Localization language. Valid values:
     *
     * @return array
     */
    public function getMaps($call_params)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'arenas/?';
        return $this->getData();
    }

    /**
     * @param array $call_params
     * fields 	string|array
     *    Response field. The fields are separated with commas. Embedded fields are separated with dots. To exclude a field, use “-” in front of its name. In case the parameter is not defined, the method returns all fields.
     * language 	string
     *    Localization language. Valid values:
     *
     * @return array
     */
    public function getTankopediaInfo($call_params)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'info/?';
        return $this->getData();
    }
}