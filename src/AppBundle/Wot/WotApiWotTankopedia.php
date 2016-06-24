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
     * @param $call_params array
     * fields 	string, list
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
}