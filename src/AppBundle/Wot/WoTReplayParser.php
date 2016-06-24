<?php

namespace AppBundle\Wot;

use Zend\Serializer\Adapter\PythonPickle;

class WoTReplayParser {

    private $result = array();
    private $fp;

    const VERSION = "0.2";
    private $debug;

    private $injuredCrew;
    private $damagedModules;
    private $destroyedModules;

    private $achievements;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
        $this->init();
    }



    public function parse($filename)
    {
        $versionString = "wot-replay-parser (" . self::VERSION . ") by vonLeeb_pl@PSQD";
        $vehicleInfo = array(); //temp array to hold all vehicle data
        $battleInfo = array(); //temp array to hold various battle info stuff

        $this->printDebug($versionString);
        $this->addResult("common.parser", $versionString);

        $this->printDebug("Processing: $filename");

        if (!is_readable($filename) || ($this->fp = @fopen($filename, "rb")) === false) {
            return $this->error("cannot read file $filename");
        }

        // Read the number of blocks in the replay file
        $this->seek(4);
        $blockCount = $this->funpack("I", 4);
        $this->printDebug("Found {$blockCount} blocks");

        if ($blockCount == 0) {
            return $this->error("unknown file structure");
        }

        // more than 5 blocks indicates an "advanced" replay?
        if ($blockCount > 5) {
            $this->addResult("common.message", "uncompressed replay");
            $this->parseAdvanced();
            if ($this->result["datablock_advanced"]["valid"] == 1) {
                //TODO: finish this!
            } else {
                return $this->error("replay incompatible");
            }
        } else {
            $currentBlockIndex = 1;
            $currentBlockPointer = 8;

            //so 5 or less blocks indicates a... "basic" replay?
            while ($currentBlockIndex <= $blockCount) {
                try {
                    $this->printDebug("Retrieving data for block {$currentBlockIndex}");

                    //read in the block size and data
                    $this->seek($currentBlockPointer);
                    $blockSize = $this->funpack("I", 4);
                    $blockData = $this->read($blockSize);

                    //try to json decode the string
                    $decodedBlockData = json_decode($blockData, true);

                    if ($decodedBlockData === null) {
                        $this->printDebug("Error with JSON in block {$currentBlockIndex}");
                        $this->printDebug("Attempting to unpickle block {$currentBlockIndex}");

                        //this will throw an exception if there's an error
                        $pickle = new PythonPickle();
                        $decodedBlockData = $pickle->unserialize($blockData);
                        if (!is_array($decodedBlockData)) {
                            throw new \Exception("Unpickled data but it wasn't a valid array.");
                        }
                    }

                    if ($this->debug)
					    $this->addResult("datablock_{$currentBlockIndex}", $decodedBlockData);

                    $this->printDebug("Successfully extracted data from block {$currentBlockIndex}");

                    //"vehicles" have the info required to rebuild each vehicles game performance. But, because
                    //its split across multiple blocks, temporarily store it so we can put into the results later.
                    if (($vehicles = $this->array_get_key_recursive("vehicles", $decodedBlockData)) !== false) {
                        foreach($vehicles as &$vehicle) {
                            if (array_key_exists("achievements", $vehicle)) {
                                $vehicle["achievements"] = $this->getAchievements($vehicle["achievements"]);
                            }

                        }
                        $vehicleInfo = $this->mergeArrays($vehicleInfo, $vehicles);
                    }

                    if ($currentBlockIndex == 1) {
                        $battleInfo['playerID'] = $decodedBlockData['playerID'];
                        $battleInfo['playerName'] = $decodedBlockData['playerName'];
                        $battleInfo['playerVehicle'] = $decodedBlockData['playerVehicle'];
                        $battleInfo['gameplayID'] = $decodedBlockData['gameplayID'];
                        $battleInfo['gameplayIDString'] = $this->getGameplayType($battleInfo['gameplayID']);
                        $battleInfo['battleType'] = $decodedBlockData['battleType'];
                        $battleInfo['battleTypeString'] = $this->getBattleType($battleInfo['battleType']);
                        $battleInfo['mapName'] = $decodedBlockData['mapName'];
                        $battleInfo['dateTime'] = $decodedBlockData['dateTime'];
                    }

                    //more vehicle data in a stupid spot, WHAT THE FUCK?
                    if ($currentBlockIndex == 2) {
                        $vehicleInfo = $this->mergeArrays($vehicleInfo, $decodedBlockData[1]);
                    }

                    //"personal" contains all the info pertinent to the player, ie: data for battle results screen
                    if (($personalInfo = $this->array_get_key_recursive("personal", $decodedBlockData)) !== false)
                    {
                        // check if personal info is of fancy deep structure
                        if(!array_key_exists('xp', $personalInfo))
                        {
                            // we have to flatten structure of perssonal info
                            $personalInfo = $this->flattenArray($personalInfo);
                        }
                        //decode each critical hit on another player into a nice array
                        // details are in personal info in sub arrays of unknown title
                        foreach ($personalInfo["details"] as &$player) {
                                $player["crits"] = $this->decodeCrits($player["crits"]);
                            }
                        $personalInfo["achievements"] = $this->getAchievements($personalInfo['club']["achievements"]);
                        $this->addResult("player", $personalInfo);
                    }

                    if (($playersInfo = $this->array_get_key_recursive("players", $decodedBlockData)) !== false) {
                        $this->addResult("players", $playersInfo);
                    }

                    if (($commonInfo = $this->array_get_key_recursive('common', $decodedBlockData)) !== false) {
                        $battleInfo = $this->mergeArrays($battleInfo, $commonInfo);
                    }

                    if (($arenaInfo = $this->array_get_key_recursive("arenaUniqueID", $decodedBlockData)) !== false) {
                        $this->addResult("arena", $arenaInfo);
                    }

                    if (array_key_exists("clientVersionFromExe", $decodedBlockData) !== false) {
                        $replayVersionString = $this->cleanVersionString($decodedBlockData["clientVersionFromExe"]);
                        $this->addResult("common.replay_version", $replayVersionString);
                        $this->printDebug("Replay version: $replayVersionString}");
                    }

                    $this->addResult("common.message", "ok");
                } catch (\Exception $ex) {
                    return $this->error($ex->getMessage());
                }

                $currentBlockIndex++;
                //skip over the block data and the block size
                $currentBlockPointer += $blockSize+4;
            }
        }
        $this->addResult("battle", $battleInfo);
        $this->addResult("vehicles", $vehicleInfo);
        fclose($this->fp);
        //$tmp = $this->decryptFile($filename, $currentBlockPointer);
        //$out = $this->decompressFile($tmp);

        //return $this->renderJson();
        return $this->result;
    }

    private function decompressFile($fn)
    {
        # Thanks to https://github.com/marklr/wotanalysis
        echo 'Decompressing';
        $i = fopen($fn, 'rb');
        $o = fopen($fn.'.out', 'wb');
        fwrite($o, zlib_decode(fread($i, filesize($fn))));

        return $fn.'out';

    }

    private function decryptFile($fn, $offset = 0)
    {
        $key = hex2bin('DE72BEA0DE04BEB1DEFEBEEFDEADBEEF');
        for ($i = 0; $i < strlen($key); $i++)
        {
            $c[$i] = ord(substr($key, $i, 1));
        }
        $bc = 0;
        $pb = null;

        echo 'Decrypting from offset: $offset';

        $of = $fn.'.tmp';

        $f = fopen($fn, 'rb');
        fseek($f, $offset+8);

        $out = fopen($of, 'wb');

        while (true)
        {
            $b = fread($f, 8);
            if (!$b) break;

            $b = str_pad($b, 8, chr(0)); //pad for correct blocksize

            $db = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $b, MCRYPT_MODE_ECB);
            if ($pb) {
                $db = $db ^ $pb;
            }
            $pb = $db;
            fwrite($out, $db);
        }
        fclose($out);
        fclose($f);
        return $of;
    }

    private function parseAdvanced()
    {
        $advanced = array();
        $this->rewind();
        $this->seek(12);
        $versionLength = $this->funpack("C", 1);
        if (!$this->isSupportedReplay($versionLength)) {
            $this->addResult("datablock_advanced.valid", 0);
            $this->printDebug("Unsupported replay: versionlength : {$versionLength}");
            return;
        }

        //move forward 3 spaces, if you pass go, collect $200
        $this->advance(3);

        //WOT version this replay is from
        $advanced["replay_version"] = $this->cleanVersionString($this->read($versionLength));

        $this->advance(51+$versionLength);
        $advanced["playername"] = $this->read($this->funpack("C", 1));
        $advanced["arenaUniqueID"] = $this->funpack("V", 2);
        $advanced["arenaCreateTime"] = $this->funpack("V", 2);
        $advanced["arenaUniqueID"] .= $advanced["arenaCreateTime"];

        $arenaTypeID = $this->funpack("I", 4);
        $advanced["gameplayID"] = $arenaTypeID >> 16;
        $advanced["arenaTypeId"] = $arenaTypeID & 32767;

        $advanced["bonusType"] = $this->funpack("C", 1);
        $advanced["guiType"] = $this->funpack("C", 1);

        $advancedLength = $this->funpack("C", 1);

        //todo: finish this!


        return;
    }

    private function isSupportedReplay($version)
    {
        return in_array($version, array(10,11));
    }

    private function cleanVersionString($version)
    {
        return str_replace(array(", ", ". ", " "), ".", $version);
    }

    private function getBattleType($type)
    {
        switch ($type) {
            case 1:
                return 'Standard Battle';
            case 2:
                return 'Training Room Battle';
            case 3:
                return 'Tank Company Battle';
            case 4:
                return 'Tournament Battle';
            case 5:
                return 'Clan War Battle';
            case 6:
                return 'Tutorial Battle';
            case 13:
                return 'Event Battle';
            default:
                return 'Unknown Battle';
        }
    }

    private function getGameplayType($type)
    {
        switch ($type) {
            case 'ctf':
                return 'CTF';
            case 'domination':
                return 'Encounter';
            case 'assault':
                return 'Assault';
            default:
                return 'Unknown';
        }
    }

    private function getAchievements($cheevos)
    {
        $result = array();
        foreach ($cheevos as $cheevo) {
            if (array_key_exists($cheevo, $this->achievements)) {
                $result[] = $this->achievements[$cheevo];
            } else {
                $result[] = "Unknown Achivement ($cheevo)";
            }
        }
        return $result;
    }

    /**
     * Decodes the bitfield $crits, returning an array of the crit names corresponding to the bitfield values.
     * @param $crits
     * @return array
     */
    private function decodeCrits($crits)
    {
        $result = array(
            "crewman" => array(),
            "moduleDestroyed" => array(),
            "moduleDamaged" => array(),
        );

        if ($crits <= 0)
            return $result;

        foreach($this->injuredCrew as $crewName => $bitmask) {
            if (($crits & $bitmask) == $bitmask) {
                $result["crewman"][] = $crewName;
            }
        }

        foreach($this->destroyedModules as $moduleName => $bitmask) {
            if (($crits & $bitmask) == $bitmask) {
                $result["moduleDestroyed"][] = $moduleName;
            }
        }

        foreach($this->damagedModules as $moduleName => $bitmask) {
            if (($crits & $bitmask) == $bitmask) {
                $result["moduleDamaged"][] = $moduleName;
            }
        }

        return $result;
    }

    /**
     * Prints the decimal, hexidecimal and bit pattern of a number, useful for debugging
     * bitfields.
     * @param $v
     */
    private function printBits($v)
    {
        if ($this->debug) {
            printf("%d %x %b\n", $v, $v, $v);
        }
    }

    /**
     * Unpacks data from the replay. Wrapper around php's unpack().
     * If $format == "I", this will return a string representing an unsigned int.
     * @param $format - pack()/unpack() format
     * @param $length - length of data to read
     * @return mixed
     */
    private function funpack($format, $length)
    {
        $res = unpack($format, $this->read($length));
        if ($format == "I") {
            $res = sprintf("%u", $res[1]);
        } else {
            $res = $res[1];
        }
        return $res;
    }

    private function advance($length)
    {
        return fseek($this->fp, ftell($this->fp) + $length);
    }

    private function rewind()
    {
        return rewind($this->fp);
    }

    private function seek($offset)
    {
        return fseek($this->fp, $offset);
    }

    private function read($length)
    {
        return fread($this->fp, $length);
    }

    /**
     * Write a fatal error into the result array, then renders the json into a string.
     * @param $err - Error message
     * @return string - Json encoded results
     */
    private function error($err)
    {
        if ($this->debug) {
            $this->printDebug("Error: $err");
        }
        $this->addResult("common.message", $err);
        return $this->renderJson();
    }

    /**
     * Recursively merges 2 arrays, preserving all keys (even numeric). if a key exists in ary1 and ary2, and its value
     * is an array, it will recursively merge those arrays. If the value is not an array, the value from 2 will replace 1.
     * @param $ary1
     * @param $ary2
     * @return array
     */
    private function mergeArrays($ary1, $ary2)
    {
        $result = array();
        foreach($ary1 as $key1 => $value1) {
            if (array_key_exists($key1, $ary2) && is_array($value1) && is_array($ary1[$key1])) {
                $result[$key1] = $this->mergeArrays($ary1[$key1], $ary2[$key1]);
                unset($ary2[$key1]);
            } else {
                $result[$key1] = $value1;
            }
        }
        foreach($ary2 as $k => $v) {
            $result[$k] = $v;
        }
        return $result;
    }

    /**
     * Flattens array, all nested arrays are now moved up
     * @param $arr
     * @return array
     */
    private function flattenArray($arr)
    {
        $result = array();
        foreach ($arr as $k => $v)
        {
            if (is_array($v))
            {
                $result = $this->mergeArrays($result, $v);
            }
            else
            {
                $result[$k] = $v;
            }
        }

        return $result;
    }

    /**
     * Adds a result into the results array.
     *
     * @param array|string $keys - Key structure to set $value to. Example: passing array("foo", "baz") or "foo.baz"
     *  will set $result["foo"]["baz"] to $value;
     * @param $value - Value to set
     * @param $ar - Do not use. Internal recursion use only.
     */
    private function addResult($keys, $value, &$ar = null)
    {
        if ($ar === null) {
            $ar = &$this->result;
        }

        if (!is_array($keys)) {
            $keys = explode(".", $keys);
        }

        $key = array_shift($keys);
        if (!array_key_exists($key, $ar)) {
            $ar[$key] = array();
        }

        if (count($keys) == 0) {
            $ar[$key] = $value;
        } else {
            $this->addResult($keys, $value, $ar[$key]);
        }
    }

    /**
     * Recursively traverse an array and return the value of the first $key it finds.
     * Returns False if $key does not exist anywhere.
     * @param $key
     * @param $ary
     * @return bool
     */
    private function array_get_key_recursive($key, $ary) {
        //early out
        if (array_key_exists($key, $ary)) {
            return $ary[$key];
        }

        foreach($ary as $k=>$v) {
            if (is_array($v)) {
                return $this->array_get_key_recursive($key, $v);
            }
        }

        return false;
    }

    /**
     * Renders some JSON
     * @return string
     */
    private function renderJson()
    {
        return json_encode($this->result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    private function printDebug($line)
    {
        if ($this->debug === true) {
            print("$line\n");
        }
    }

    private function init()
    {
        $this->damagedModules = array(
            "engine" => 1,
            "ammoRack" => 1 << 1,
            "fuelTank" => 1 << 2,
            "radio" => 1 << 3,
            "track" => 1 << 4,
            "gun" => 1 << 5,
            "turretRing" => 1 << 6,
            "viewport" => 1 << 7
        );

        $this->destroyedModules = array(
            "engine" => 1 << 12,
            "ammoRack" => 1 << 13,
            "fuelTank" => 1 << 14,
            "radio" => 1 << 15,
            "track" => 1 << 16,
            "gun" => 1 << 17,
            "turretRing" => 1 << 18,
            "viewport" => 1 << 19
        );

        $this->injuredCrew = array(
            "commander" => 1 << 24,
            "driver" => 1 << 25,
            "radioman" => 1 << 26,
            "gunner" => 1 << 27,
            "loader" => 1 << 28
        );

        $this->achievements = array(
            1 => "xp",
            2 => "maxXP",
            3 => "battlesCount",
            4 => "wins",
            5 => "losses",
            6 => "survivedBattles",
            7 => "lastBattleTime",
            8 => "battleLifeTime",
            9 => "winAndSurvived",
            10 => "battleHeroes",
            11 => "frags",
            12 => "maxFrags",
            13 => "frags8p",
            14 => "fragsBeast",
            15 => "shots",
            16 => "directHits",
            17 => "spotted",
            18 => "damageDealt",
            19 => "damageReceived",
            20 => "treesCut",
            21 => "capturePoints",
            22 => "droppedCapturePoints",
            23 => "sniperSeries",
            24 => "maxSniperSeries",
            25 => "invincibleSeries",
            26 => "maxInvincibleSeries",
            27 => "diehardSeries",
            28 => "maxDiehardSeries",
            29 => "killingSeries",
            30 => "maxKillingSeries",
            31 => "piercingSeries",
            32 => "maxPiercingSeries",
            34 => "warrior",
            35 => "invader",
            36 => "sniper",
            37 => "defender",
            38 => "steelwall",
            39 => "supporter",
            40 => "scout",
            41 => "medalKay",
            42 => "medalCarius",
            43 => "medalKnispel",
            44 => "medalPoppel",
            45 => "medalAbrams",
            46 => "medalLeClerc",
            47 => "medalLavrinenko",
            48 => "medalEkins",
            49 => "medalWittmann",
            50 => "medalOrlik",
            51 => "medalOskin",
            52 => "medalHalonen",
            53 => "medalBurda",
            54 => "medalBillotte",
            55 => "medalKolobanov",
            56 => "medalFadin",
            57 => "tankExpert",
            58 => "titleSniper",
            59 => "invincible",
            60 => "diehard",
            61 => "raider",
            62 => "handOfDeath",
            63 => "armorPiercer",
            64 => "kamikaze",
            65 => "lumberjack",
            66 => "beasthunter",
            67 => "mousebane",
            68 => "creationTime",
            69 => "maxXPVehicle",
            70 => "maxFragsVehicle",
            72 => "evileye",
            73 => "medalRadleyWalters",
            74 => "medalLafayettePool",
            75 => "medalBrunoPietro",
            76 => "medalTarczay",
            77 => "medalPascucci",
            78 => "medalDumitru",
            79 => "markOfMastery",
            80 => "xp",
            81 => "battlesCount",
            82 => "wins",
            83 => "losses",
            84 => "survivedBattles",
            85 => "frags",
            86 => "shots",
            87 => "directHits",
            88 => "spotted",
            89 => "damageDealt",
            90 => "damageReceived",
            91 => "capturePoints",
            92 => "droppedCapturePoints",
            93 => "xp",
            94 => "battlesCount",
            95 => "wins",
            96 => "losses",
            97 => "survivedBattles",
            98 => "frags",
            99 => "shots",
            100 => "directHits",
            101 => "spotted",
            102 => "damageDealt",
            103 => "damageReceived",
            104 => "capturePoints",
            105 => "droppedCapturePoints",
            106 => "medalLehvaslaiho",
            107 => "medalNikolas",
            108 => "fragsSinai",
            109 => "sinai",
            110 => "heroesOfRassenay",
            111 => "mechanicEngineer",
            112 => "tankExpert0",
            113 => "tankExpert1",
            114 => "tankExpert2",
            115 => "tankExpert3",
            116 => "tankExpert4",
            117 => "tankExpert5",
            118 => "tankExpert6",
            119 => "tankExpert7",
            120 => "tankExpert8",
            121 => "tankExpert9",
            122 => "tankExpert10",
            123 => "tankExpert11",
            124 => "tankExpert12",
            125 => "tankExpert13",
            126 => "tankExpert14",
            127 => "mechanicEngineer0",
            128 => "mechanicEngineer1",
            129 => "mechanicEngineer2",
            130 => "mechanicEngineer3",
            131 => "mechanicEngineer4",
            132 => "mechanicEngineer5",
            133 => "mechanicEngineer6",
            134 => "mechanicEngineer7",
            135 => "mechanicEngineer8",
            136 => "mechanicEngineer9",
            137 => "mechanicEngineer10",
            138 => "mechanicEngineer11",
            139 => "mechanicEngineer12",
            140 => "mechanicEngineer13",
            141 => "mechanicEngineer14",
            142 => "gold",
            143 => "medalBrothersInArms",
            144 => "medalCrucialContribution",
            145 => "medalDeLanglade",
            146 => "medalTamadaYoshio",
            147 => "bombardier",
            148 => "huntsman",
            149 => "alaric",
            150 => "sturdy",
            151 => "ironMan",
            152 => "luckyDevil",
            153 => "fragsPatton",
            154 => "pattonValley",
            155 => "xpBefore8_8",
            156 => "battlesCountBefore8_8",
            157 => "originalXP",
            158 => "damageAssistedTrack",
            159 => "damageAssistedRadio",
            160 => "mileage",
            161 => "directHitsReceived",
            162 => "noDamageDirectHitsReceived",
            163 => "piercingsReceived",
            164 => "explosionHits",
            165 => "piercings",
            166 => "explosionHitsReceived",
            167 => "mechanicEngineerStrg",
            168 => "tankExpertStrg",
            169 => "originalXP",
            170 => "damageAssistedTrack",
            171 => "damageAssistedRadio",
            173 => "directHitsReceived",
            174 => "noDamageDirectHitsReceived",
            175 => "piercingsReceived",
            176 => "explosionHitsReceived",
            177 => "explosionHits",
            178 => "piercings",
            179 => "originalXP",
            180 => "damageAssistedTrack",
            181 => "damageAssistedRadio",
            183 => "directHitsReceived",
            184 => "noDamageDirectHitsReceived",
            185 => "piercingsReceived",
            186 => "explosionHitsReceived",
            187 => "explosionHits",
            188 => "piercings",
            189 => "xp",
            190 => "battlesCount",
            191 => "wins",
            192 => "losses",
            193 => "survivedBattles",
            194 => "frags",
            195 => "shots",
            196 => "directHits",
            197 => "spotted",
            198 => "damageDealt",
            199 => "damageReceived",
            200 => "capturePoints",
            201 => "droppedCapturePoints",
            202 => "originalXP",
            203 => "damageAssistedTrack",
            204 => "damageAssistedRadio",
            206 => "directHitsReceived",
            207 => "noDamageDirectHitsReceived",
            208 => "piercingsReceived",
            209 => "explosionHitsReceived",
            210 => "explosionHits",
            211 => "piercings",
            212 => "xpBefore8_9",
            213 => "battlesCountBefore8_9",
            214 => "xpBefore8_9",
            215 => "battlesCountBefore8_9",
            216 => "winAndSurvived",
            217 => "frags8p",
            218 => "maxDamage",
            219 => "maxDamageVehicle",
            220 => "maxXP",
            221 => "maxXPVehicle",
            222 => "maxFrags",
            223 => "maxFragsVehicle",
            224 => "maxDamage",
            225 => "maxDamageVehicle",
            226 => "battlesCount",
            227 => "sniper2",
            228 => "mainGun",
            229 => "wolfAmongSheep",
            230 => "wolfAmongSheepMedal",
            231 => "geniusForWar",
            232 => "geniusForWarMedal",
            233 => "kingOfTheHill",
            234 => "tacticalBreakthroughSeries",
            235 => "maxTacticalBreakthroughSeries",
            236 => "armoredFist",
            237 => "tacticalBreakthrough",
            238 => "potentialDamageReceived",
            239 => "damageBlockedByArmor",
            240 => "potentialDamageReceived",
            241 => "damageBlockedByArmor",
            242 => "potentialDamageReceived",
            243 => "damageBlockedByArmor",
            244 => "potentialDamageReceived",
            245 => "damageBlockedByArmor",
            246 => "battlesCountBefore9_0",
            247 => "battlesCountBefore9_0",
            248 => "battlesCountBefore9_0",
            249 => "battlesCountBefore9_0",
            250 => "xp",
            251 => "battlesCount",
            252 => "wins",
            253 => "winAndSurvived",
            254 => "losses",
            255 => "survivedBattles",
            256 => "frags",
            257 => "frags8p",
            258 => "shots",
            259 => "directHits",
            260 => "spotted",
            261 => "damageDealt",
            262 => "damageReceived",
            263 => "capturePoints",
            264 => "droppedCapturePoints",
            265 => "originalXP",
            266 => "damageAssistedTrack",
            267 => "damageAssistedRadio",
            268 => "directHitsReceived",
            269 => "noDamageDirectHitsReceived",
            270 => "piercingsReceived",
            271 => "explosionHitsReceived",
            272 => "explosionHits",
            273 => "piercings",
            274 => "potentialDamageReceived",
            275 => "damageBlockedByArmor",
            276 => "maxXP",
            277 => "maxXPVehicle",
            278 => "maxFrags",
            279 => "maxFragsVehicle",
            280 => "maxDamage",
            281 => "maxDamageVehicle",
            282 => "guardsman",
            283 => "makerOfHistory",
            284 => "bothSidesWins",
            285 => "weakVehiclesWins",
            286 => "godOfWar",
            287 => "fightingReconnaissance",
            288 => "fightingReconnaissanceMedal",
            289 => "willToWinSpirit",
            290 => "crucialShot",
            291 => "crucialShotMedal",
            292 => "forTacticalOperations",
            293 => "battleCitizen",
            294 => "movingAvgDamage",
            295 => "marksOnGun",
            296 => "medalMonolith",
            297 => "medalAntiSpgFire",
            298 => "medalGore",
            299 => "medalCoolBlood",
            300 => "medalStark",
            301 => "histBattle1_battlefield",
            302 => "histBattle1_historyLessons",
            303 => "histBattle2_battlefield",
            304 => "histBattle2_historyLessons",
            305 => "histBattle3_battlefield",
            306 => "histBattle3_historyLessons",
            307 => "histBattle4_battlefield",
            308 => "histBattle4_historyLessons",
            309 => "xp",
            310 => "battlesCount",
            311 => "wins",
            312 => "winAndSurvived",
            313 => "losses",
            314 => "survivedBattles",
            315 => "frags",
            316 => "frags8p",
            317 => "shots",
            318 => "directHits",
            319 => "spotted",
            320 => "damageDealt",
            321 => "damageReceived",
            322 => "capturePoints",
            323 => "droppedCapturePoints",
            324 => "originalXP",
            325 => "damageAssistedTrack",
            326 => "damageAssistedRadio",
            327 => "directHitsReceived",
            328 => "noDamageDirectHitsReceived",
            329 => "piercingsReceived",
            330 => "explosionHitsReceived",
            331 => "explosionHits",
            332 => "piercings",
            333 => "potentialDamageReceived",
            334 => "damageBlockedByArmor",
            335 => "maxXP",
            336 => "maxXPVehicle",
            337 => "maxFrags",
            338 => "maxFragsVehicle",
            339 => "maxDamage",
            340 => "maxDamageVehicle",
            341 => "xp",
            342 => "battlesCount",
            343 => "wins",
            344 => "winAndSurvived",
            345 => "losses",
            346 => "survivedBattles",
            347 => "frags",
            348 => "frags8p",
            349 => "shots",
            350 => "directHits",
            351 => "spotted",
            352 => "damageDealt",
            353 => "damageReceived",
            354 => "capturePoints",
            355 => "droppedCapturePoints",
            356 => "originalXP",
            357 => "damageAssistedTrack",
            358 => "damageAssistedRadio",
            359 => "directHitsReceived",
            360 => "noDamageDirectHitsReceived",
            361 => "piercingsReceived",
            362 => "explosionHitsReceived",
            363 => "explosionHits",
            364 => "piercings",
            365 => "potentialDamageReceived",
            366 => "damageBlockedByArmor",
            367 => "maxXP",
            368 => "maxXPVehicle",
            369 => "maxFrags",
            370 => "maxFragsVehicle",
            371 => "maxDamage",
            372 => "maxDamageVehicle",
            373 => "xp",
            374 => "battlesCount",
            375 => "wins",
            376 => "winAndSurvived",
            377 => "losses",
            378 => "survivedBattles",
            379 => "frags",
            380 => "frags8p",
            381 => "shots",
            382 => "directHits",
            383 => "spotted",
            384 => "damageDealt",
            385 => "damageReceived",
            386 => "capturePoints",
            387 => "droppedCapturePoints",
            388 => "originalXP",
            389 => "damageAssistedTrack",
            390 => "damageAssistedRadio",
            391 => "directHitsReceived",
            392 => "noDamageDirectHitsReceived",
            393 => "piercingsReceived",
            394 => "explosionHitsReceived",
            395 => "explosionHits",
            396 => "piercings",
            397 => "potentialDamageReceived",
            398 => "damageBlockedByArmor",
            399 => "xp",
            400 => "battlesCount",
            401 => "wins",
            402 => "winAndSurvived",
            403 => "losses",
            404 => "survivedBattles",
            405 => "frags",
            406 => "frags8p",
            407 => "shots",
            408 => "directHits",
            409 => "spotted",
            410 => "damageDealt",
            411 => "damageReceived",
            412 => "capturePoints",
            413 => "droppedCapturePoints",
            414 => "originalXP",
            415 => "damageAssistedTrack",
            416 => "damageAssistedRadio",
            417 => "directHitsReceived",
            418 => "noDamageDirectHitsReceived",
            419 => "piercingsReceived",
            420 => "explosionHitsReceived",
            421 => "explosionHits",
            422 => "piercings",
            423 => "potentialDamageReceived",
            424 => "damageBlockedByArmor",
            425 => "fortResourceInSorties",
            426 => "maxFortResourceInSorties",
            427 => "fortResourceInBattles",
            428 => "maxFortResourceInBattles",
            429 => "defenceHours",
            430 => "successfulDefenceHours",
            431 => "attackNumber",
            432 => "enemyBasePlunderNumber",
            433 => "enemyBasePlunderNumberInAttack",
            434 => "fortResourceInSorties",
            435 => "maxFortResourceInSorties",
            436 => "fortResourceInBattles",
            437 => "maxFortResourceInBattles",
            438 => "defenceHours",
            439 => "successfulDefenceHours",
            440 => "attackNumber",
            441 => "enemyBasePlunderNumber",
            442 => "enemyBasePlunderNumberInAttack",
            443 => "production",
            444 => "middleBattlesCount",
            445 => "championBattlesCount",
            446 => "absoluteBattlesCount",
            447 => "fortResourceInMiddle",
            448 => "fortResourceInChampion",
            449 => "fortResourceInAbsolute",
            450 => "battlesHours",
            451 => "attackCount",
            452 => "defenceCount",
            453 => "enemyBaseCaptureCount",
            454 => "ownBaseLossCount",
            455 => "ownBaseLossCountInDefence",
            456 => "enemyBaseCaptureCountInAttack",
            457 => "maxXP",
            458 => "maxXPVehicle",
            459 => "maxFrags",
            460 => "maxFragsVehicle",
            461 => "maxDamage",
            462 => "maxDamageVehicle",
            463 => "maxXP",
            464 => "maxXPVehicle",
            465 => "maxFrags",
            466 => "maxFragsVehicle",
            467 => "maxDamage",
            468 => "maxDamageVehicle",
            469 => "promisingFighter",
            470 => "promisingFighterMedal",
            471 => "heavyFire",
            472 => "heavyFireMedal",
            473 => "ranger",
            474 => "rangerMedal",
            475 => "fireAndSteel",
            476 => "fireAndSteelMedal",
            477 => "pyromaniac",
            478 => "pyromaniacMedal",
            479 => "noMansLand",
            480 => "damageRating",
            481 => "citadel",
            482 => "conqueror",
            483 => "fireAndSword",
            484 => "crusher",
            485 => "counterblow",
            486 => "soldierOfFortune",
            487 => "kampfer",
            488 => "WFC2014WinSeries",
            489 => "maxWFC2014WinSeries",
            490 => "WFC2014",
            491 => "histBattle5_battlefield",
            492 => "histBattle5_historyLessons",
            493 => "histBattle6_battlefield",
            494 => "histBattle6_historyLessons"
        );
    }

    public function parsePackages($filename)
    {
        $paysize = 0;
        $ptype = 0;
        $clock = 0;
        $payload = "";
        $offset = 0;

        if (!is_readable($filename) || ($this->fp = @fopen($filename, "rb")) === false) {
            return $this->error("cannot read file $filename");
        }

        while (true)
        {
            $this->seek($offset);
            $paysize = $this->funpack('I', 4);
            $ptype = $this->funpack('I', 4);
            $clock = $this->funpack('f', 4);
            switch ($ptype)
            {
                case 0x0a:
                    $player = $this->funpack('I', 4);
                    $t1 = $this->funpack('I', 4);
                    $t2 = $this->funpack('I', 4);
                    $x = $this->funpack('f', 4);
                    $z = $this->funpack('f', 4);
                    $y = $this->funpack('f', 4);
                    break;
            }
            if ($ptype == 0xffffffff)
            {
                break;
            }

            //$payload = $this->read($paysize);

            $offset += $paysize + 12;



        }

    }
}
