<?php
/**
 * Class which provides generic functions to get data from the official World of Tanks web api.
 *
 * @package wot-api
 */
namespace AppBundle\Wot;

use Symfony\Component\Config\Definition\Exception\Exception;

Class WotApiCall
{
	/**
	 * @var string default = 'EU'
	 */
	protected $_region = 'EU';

	/**
	 * @var array of app_ids, can be just one value, if more they are used sequentially with every api call
     * TODO: move to config class
	 */
    protected $_app_id = [   0 => 'bf50bd80740ecfaa1c587f5efc3772b9',
                             1 => '2ccca06d783770bbd7b7c35670d3f5b9',
                             2 => 'f523731f069c8df938ef6d01c66efddd'];

	/**
	 * @var string default = 'en'
	 */
	protected $_language = 'en';

	/**
	 * @var string to build api call
	 */
	protected $_api_url = 'http://api.worldoftanks.eu';

	/**
	 * @var int default = 100
	 */
	protected $_limit = 100;

	/**
	 * intarnal call counter to shuffle app_id used
	*/
	protected static $_cnt = 0;

    /**
     * @var array stores request from api call
    */
    protected $_data;

    /**
     * @var string or array
     */
    protected $_call_params = [];


	/**
	 * @param string $region
	 * @param string $language
     * app_id are get form WOtApiConfig class once and stored as static values
	 */
	public function __construct($region = null, $language = null)
	{
		if ($region) $this->setRegion($region);
        if ($language) $this->_language = $language;

		self::$_cnt++;
	}

	/**
	 * @param string $region
	 */
	public function setRegion($region)
	{
        $this->_region = $region;

        switch ($this->_region) {
            case 'NA':
                $this->_api_url = 'http://api.worldoftanks.com';
                break;

            case 'ASIA':
                $this->_api_url = 'http://api.worldoftanks.asia';
                break;

            case 'KR':
                $this->_api_url = 'http://api.worldoftanks.kr';
                break;

            case 'RU':
                $this->_api_url = 'http://api.worldoftanks.ru';
                break;

            default:
                // fallback to default EU region
                $this->_region = 'EU';
                $this->_api_url = 'http://api.worldoftanks.eu';
                break;
        }
	}

	/**
	 * @param string $lang
	 */
	public function setLang($lang = 'ru')
	{
		$this->lang = $lang;
	}

	/**
	 * @param int $val
	 */
	public function setLimit($val)
	{
		$this->_limit = (int)$val;
	}


    /**
     * process api call and return received data
     * @return array
     */
    public function getData()
    {
        $this->_api_url .= 'application_id='.$this->_app_id[self::$_cnt % count($this->_app_id)].'&language='.$this->_language;

        foreach ($this->_call_params as $index => $call_param) {
		   if (is_array($call_param))
			   $this->_api_url .= '&'.$index.'='.implode(',', $call_param);
		   else
			   $this->_api_url .= '&'.$index.'='.$call_param;
        }
        $this->_processRequest();
        return $this->_data;
    }

	/**
	 * Handles the invoked request.
	 *
	 */
	protected function _processRequest()
	{
        // call api and store them
        try{
            $obj = json_decode(file_get_contents($this->_api_url), true);
        }
        catch(Exception $e)
        {
            throw new \Exception('WOT API service not available!!');
        }
        switch ($obj['status']) {
            case 'ok':
                $this->_data = $obj['data'];
                return;
            default:
                throw new \Exception('Error: '.$obj['error']['message'].' code: '.$obj['error']['code'].' field: '.$obj['error']['field'].' value: '.$obj['error']['value']);
        }
	}
}


