<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-05-10
 * Time: 18:21
 */

namespace AppBundle\Wot;


use Zend\Serializer\Adapter\PythonPickle;

class WotReplayParser_
{
    const PARSER_VERSION = "0.9.8.0";

    private static $option_console = 0;
    private static $option_advanced = 1;
    private static $option_chat = 1;
    private static $option_server = 1;

    private static $filename_source = "";

    private $replay_version = "0.0.0.0";
    private $replay_version_dict = ['0', '0', '0', '0'];



    public function dumpJson($mydict, $filename_source, $exitcode)
    {
        if ($exitcode == 0)
        {
            $mydict['common']['status'] = "ok";
        }
        else
        {
            $mydict['common']['status'] = "error";
            $this->printMessage("Errors occurred: ".$mydict['common']['message']);
//  		write_to_log("WOTRP2J: Err on " + str(mydict['common']['message']))
        }

        $json_data = json_encode($mydict, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if(!$json_data)
        {
            throw new Exception('JSON Error: '.json_last_error_msg());
        }

        if (self::$option_console == 0)
        {
            $filename_target = pathinfo($filename_source, PATHINFO_FILENAME).'json';
            file_put_contents($filename_target, $json_data);
        }
        else
        {
            echo $json_data;
        }

        unlink($filename_source.".tmp");
	    unlink($filename_source.".tmp.out");
    }


    public function getJsonData($filename)
    {
        $cwdir = getcwd();
        chdir($cwdir);

        if (!file_exists($filename))
        {
            $this->printMessage($filename.' does not exist!');
            die;
        }

        //$file_json = fopen($filename, 'r');

        try {
            $str = file_get_contents($filename);
            $file_data = json_decode($str, true);
        } catch (\Exception $e)
        {
            $this->printMessage($filename.' cannot be loaded as JSON: '.$e->getMessage());
            die;
        }

        return $file_data;
    }

    public function catchFatal($message)
    {
        $this->printMessage($message);
    }

    public function decodeDetails($data)
    {
        $detail = [
            "spotted",
            "deathReason",
            "hits",
            "he_hits",
            "pierced",
            "damageDealt",
            "damageAssistedTrack",
            "damageAssistedRadio",
            "crits",
            "fire"
        ];

        $details = array();

        $binlen = floor(strlen($data) / 22);
        $datalen = 20;

        try{
            for ($x = 0; $x < $binlen; $x++)
            {
                $offset = 4 * $binlen + $x * $datalen;
                $vehic = unpack('i', substr($data, $x*4, 4))[0];
                $detail_values = unpack('CcvvvvvvIv', substr($data, $offset, $datalen));
                $details[$vehic] = array_map(null, $detail, $detail_values);
            }
        } catch (\Exception $e) {
            $this->printMessage("Cannot decode details: ".$e->getMessage());
        }

        return $details;
    }


    public function encodeUtf8(String $s)
    {
        return mb_convert_encoding($s, "UTF-8");
    }

    public function isSupportedReplay($f)
    {
        fseek($f, 12);
        $versionlength = unpack('C', fread($f, 1))[0];

        if ($versionlength == 10 or $versionlength == 11)
            return true;

        return false;
    }

    public function printMessage($message)
    {
        if (!$this::$option_console)
            echo $message;
    }

    public function extractAdvanced($fn)
    {
        $advanced = array();
        $f = fopen($fn, 'r');

        fseek($f, 12);
        $versionlength = unpack('C', fread($f, 1))[0];

        if(!$this->isSupportedReplay($f))
        {
            $advanced['valid'] = 0;
            $this->printMessage('Unsupported replay: Versionlength: $versionlength');

            return $advanced;
        }

        fread($f, 3);

        $advanced['replay_version'] = fread($f, $versionlength);
		$advanced['replay_version'] = trim(str_replace(', ', '.', $advanced['replay_version']));
        $advanced['replay_version'] = trim(str_replace('. ', '.', $advanced['replay_version']));
        $advanced['replay_version'] = trim(str_replace(' ', '.', $advanced['replay_version']));

        fseek($f, 51 + $versionlength);
        $playernamelength = unpack('C', fread($f, 1))[0];

        $advanced['playername'] = fread($f, $playernamelength);
        $advanced['arenaUniqueID'] = unpack("Q",fread($f, 8))[0];
		$advanced['arenaCreateTime'] = $advanced['arenaUniqueID'] & 4294967295;

        $advanced['arenaTypeID'] = unpack("I",fread($f, 4))[0];
		$advanced['gameplayID'] = $advanced['arenaTypeID'] >> 16;
		$advanced['arenaTypeID'] = $advanced['arenaTypeID'] & 32767;

        $advanced['bonusType'] = unpack("C",fread($f, 1))[0];
		$advanced['guiType'] = unpack("C",fread($f, 1))[0];

        $advanced['more'] = array();
		$advancedlength = unpack("C",fread($f, 1))[0];

        if ($advancedlength == 255)
        {
            $advancedlength = unpack("S",fread($f, 2))[0];
			fread($f, 1);
        }

        try {
            $advanced_pickles = fread($f, $advancedlength);
            $pickle = new PythonPickle();
            $advanced['more'] = $pickle->unserialize($advanced_pickles);
        } catch (\Exception $e) {
            $this->printMessage('cannot load advanced pickle: '.$e->getMessage());
			$this->printMessage('Position: '.ftell($f).", Length: ".$advancedlength);
        }

        fseek($f, ftell($f) + 29);

        $advancedlength = unpack("C",fread($f, 1))[0];

        if ($advancedlength == 255)
        {
            $advancedlength = unpack("S",fread($f, 2))[0];
            fread($f, 1);
        }

        $rosters = array();

        try {
            $advanced_pickles = fread($f, $advancedlength);
            $pickle = new PythonPickle();
            $rosters = $pickle->unserialize($advanced_pickles);
        } catch (\Exception $e) {
            $this->printMessage('cannot load roster pickle: '.$e->getMessage());
            $this->printMessage('Position: '.ftell($f).", Length: ".$advancedlength);
        }

        $rosterdata = array();

        foreach ($rosters as $roster)
        {
            $rosterdata[$roster[2]] = [];
			$rosterdata[$roster[2]]['internaluserID'] = $roster[0];
			$rosterdata[$roster[2]]['playerName'] = $roster[2];
			$rosterdata[$roster[2]]['team'] = $roster[3];
			$rosterdata[$roster[2]]['accountDBID'] = $roster[7];
			$rosterdata[$roster[2]]['clanAbbrev'] = $roster[8];
			$rosterdata[$roster[2]]['clanID'] = $roster[9];
			$rosterdata[$roster[2]]['prebattleID'] = $roster[10];

            $arr_sl = array_slice($roster[1], 0, 13);
            $bindata = unpack('CCvvvvvv', implode('', $arr_sl));

            $rosterdata[$roster[2]]['countryID'] = $bindata[0] >> 4 & 15;
			$rosterdata[$roster[2]]['tankID'] = $bindata[1];
			$compDescr = ($bindata[1] << 8) + $bindata[0];
			$rosterdata[$roster[2]]['compDescr'] = $compDescr;

			$rosterdata[$roster[2]]['vehicle'] = array();
/*
            # Does not make sense, will check later
            # rosterdata[roster[2]]['vehicle']['chassisID'] = bindata[2]
            # rosterdata[roster[2]]['vehicle']['engineID'] = bindata[3]
            # rosterdata[roster[2]]['vehicle']['fueltankID'] = bindata[4]
            # rosterdata[roster[2]]['vehicle']['radioID'] = bindata[5]
            # rosterdata[roster[2]]['vehicle']['turretID'] = bindata[6]
            # rosterdata[roster[2]]['vehicle']['gunID'] = bindata[7]
            */

            $flags = unpack('C', $roster[1][14])[0];

            $optional_devices_mask = $flags & 15;

            $idx = 2;
			$pos = 15;

            while ($optional_devices_mask)
            {
                if ($optional_devices_mask & 1)
                {
                    try {
                        if (count($roster[1]) >= $pos + 2)
                        {
                            $arr_sl = array_slice($roster[1], $pos, $pos + 2);
                            $m = unpack('v', implode($arr_sl))[0];
							$rosterdata[$roster[2]]['vehicle']['module_'.$idx] = $m;
                        }
                    } catch (\Exception $e) {
                        $this->printMessage('error on processing player ['.$roster[2].']: '.$e->getMessage());
                    }
                }
                else
                {
                    $rosterdata[$roster[2]]['vehicle']['module_'.$idx] = -1;
                }

                $optional_devices_mask = $optional_devices_mask >> 1;
                $idx = $idx - 1;
				$pos = $pos + 2;
            }
        }
        $advanced['roster'] = $rosterdata;

        $advanced['valid'] = 1;
	    return $advanced;
    }

    public function decompressFile($fn)
    {
        # Thanks to https://github.com/marklr/wotanalysis
        echo 'Decompressing';
        $i = fopen($fn, 'r');
        $o = fopen($fn.'out', 'w');
        fwrite($o, zlib_decode(fread($i, filesize($i))));

        return $fn.'out';

    }

    public function decryptFile($fn, $offset = 0)
    {
        $key = hex2bin('DE72BEA0DE04BEB1DEFEBEEFDEADBEEF');
        $bc = 0;
        $pb = null;

        echo 'Decrypting from offset: $offset';

        $of = $fn.'.tmp';

        $f = fopen($fn, 'r');
        fseek($f, $offset);

        $out = fopen($of, 'w');

        while (true)
        {
            $b = fread($f, 8);
            if (!$b) break;

            $b = str_pad($b, 8, chr(0)); //pad for correct blocksize

            if ($bc > 0 )
            {
                $db = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $b, MCRYPT_MODE_ECB);
                if ($pb) {
                   $db = $db ^ $pb;
                }
                $pb = $db;
                fwrite($out, $db);
            }
            $bc++;
        }
        return $of;
    }

    public function extractChat($fn)
    {
        $chats = array();
        //$f = fopen($fn, 'r');
        $s = file_get_contents($fn);
        preg_match_all("/<font.*>.*?<\/font>/", $s, $chats);

        $extracted_chat = '';
        foreach ($chats[0] as $line)
        {
            $extracted_chat = $extracted_chat.htmlentities($line).'<br/>';
        }

        return $extracted_chat;
    }

}
