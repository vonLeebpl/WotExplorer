<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-07-15
 * Time: 21:39
 */

namespace AppBundle\Wot;


class WotApiWotStronghold extends WotApiCall
{
    /**
     * WotApiWotStronghold constructor.
     */
    public function __construct()
    {
        parent::__construct( func_get_args() );
        $this->_api_url .= '/wot/stronghold/';
    }

    /**
     * @param array $call_params
     * fields 	string, list
     *      Response field. The fields are separated with commas. Embedded fields are separated with dots. To exclude a field, use “-” in front of its name. In case the parameter is not defined, the method returns all fields.
     *
     * access_token 	string
     *      Access token is used to access personal user data. The token is obtained via authentication and has expiration time.
     *account_id 	numeric, list (limit 100)
     * 
     * http://eu.wargaming.net/developers/api_reference/wot/stronghold/accountstats/
     *
     * @return array
     */
    public function getPlayerStrongholdStats($call_params)
    {
        if ($call_params) $this->_call_params = $call_params;

        $this->_api_url .= 'accountstats/?';
        return $this->getData();
    }
}