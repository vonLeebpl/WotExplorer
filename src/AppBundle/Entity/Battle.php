<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-05-20
 * Time: 21:49
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;



/**
 * Battle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BattleRepository")
 */
class Battle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Replay", mappedBy="battle", cascade={"remove"})
     */
    private $replays;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BattleAttendance", mappedBy="battle", cascade={"remove"})
     */
    private $battleAttendances;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BattleEnemyAttendance", mappedBy="battle", cascade={"remove"})
     */
    private $battleEnemyAttendances;

    /**
     * @var array
     * @ORM\Column(type="json_array")
     */
    private $dataArray;

    /**
     * @var int
     * @ORM\Column(type="bigint")
     */
    private $arenaId;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true, )
     */
    private $commander;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $commanderName;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $creatorId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, length=40)
     */
    private $mapId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, length=80)
     */
    private $mapName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, length=5)
     */
    private $score;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $datePlayed;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $clan;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $enemyClan;

    /**
     * @var integer, -1 lost, 0 draw, 1 win
     * @ORM\Column(type="integer")
     */
    private $result;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $stronghold = false;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isGoldPayed = false;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->replays = new ArrayCollection();
        $this->battleAttendances = new ArrayCollection();
        $this->battleEnemyAttendances = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dataArray
     *
     * @param array $dataArray
     *
     * @return Battle
     */
    public function setDataArray($dataArray)
    {
        $this->dataArray = $dataArray;

        return $this;
    }

    /**
     * Get dataArray
     *
     * @return array
     */
    public function getDataArray()
    {
        return $this->dataArray;
    }

    /**
     * Add replay
     *
     * @param \AppBundle\Entity\Replay $replay
     *
     * @return Battle
     */
    public function addReplay(Replay $replay)
    {
        $this->replays[] = $replay;

        return $this;
    }

    /**
     * Remove replay
     *
     * @param \AppBundle\Entity\Replay $replay
     */
    public function removeReplay(Replay $replay)
    {
        $this->replays->removeElement($replay);
    }

    /**
     * Get replays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReplays()
    {
        return $this->replays;
    }

    /**
     * Set arenaId
     *
     * @param integer $arenaId
     *
     * @return Battle
     */
    public function setArenaId($arenaId)
    {
        $this->arenaId = $arenaId;

        return $this;
    }

    /**
     * Get arenaId
     *
     * @return integer
     */
    public function getArenaId()
    {
        return $this->arenaId;
    }

    /**
     * Set commanderId
     *
     * @param Player $commander
     *
     * @return Battle
     */
    public function setCommander(Player $commander = null)
    {
        $this->commander = $commander;
        $this->commanderName = $commander->getUsername();

        return $this;
    }

    /**
     * Get commanderId
     *
     * @return Player
     */
    public function getCommander()
    {
        return $this->commander;
    }

    /**
     * Set creatorId
     *
     * @param integer $creatorId
     *
     * @return Battle
     */
    public function setCreatorId($creatorId)
    {
        $this->creatorId = $creatorId;

        return $this;
    }

    /**
     * Get creatorId
     *
     * @return integer
     */
    public function getCreatorId()
    {
        return $this->creatorId;
    }

    /**
     * Set mapId
     *
     * @param string $mapId
     *
     * @return Battle
     */
    public function setMapId($mapId)
    {
        $this->mapId = $mapId;

        return $this;
    }

    /**
     * Get mapId
     *
     * @return string
     */
    public function getMapId()
    {
        return $this->mapId;
    }

    /**
     * Set mapName
     *
     * @param string $mapName
     *
     * @return Battle
     */
    public function setMapName($mapName)
    {
        $this->mapName = $mapName;

        return $this;
    }

    /**
     * Get mapName
     *
     * @return string
     */
    public function getMapName()
    {
        return $this->mapName;
    }

    /**
     * Set datePlayed
     *
     * @param \DateTime $datePlayed
     *
     * @return Battle
     */
    public function setDatePlayed(\DateTime $datePlayed)
    {
        $this->datePlayed = $datePlayed;

        return $this;
    }

    /**
     * Get datePlayed
     *
     * @return \DateTime
     */
    public function getDatePlayed()
    {
        return $this->datePlayed;
    }

    /**
     * Set clan
     *
     * @param string $clan
     *
     * @return Battle
     */
    public function setClan($clan)
    {
        $this->clan = $clan;

        return $this;
    }

    /**
     * Get clan
     *
     * @return string
     */
    public function getClan()
    {
        return $this->clan;
    }

    /**
     * Set enemyClan
     *
     * @param string $enemyClan
     *
     * @return Battle
     */
    public function setEnemyClan($enemyClan)
    {
        $this->enemyClan = $enemyClan;

        return $this;
    }

    /**
     * Get enemyClan
     *
     * @return string
     */
    public function getEnemyClan()
    {
        return $this->enemyClan;
    }

    /**
     * Set result
     *
     * @param integer $result
     *
     * @return Battle
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return integer
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set stronghold
     *
     * @param boolean $stronghold
     *
     * @return Battle
     */
    public function setStronghold($stronghold)
    {
        $this->stronghold = $stronghold;

        return $this;
    }

    /**
     * Get stronghold
     *
     * @return boolean
     */
    public function getStronghold()
    {
        return $this->stronghold;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Battle
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add battleAttendance
     *
     * @param \AppBundle\Entity\BattleAttendance $battleAttendance
     *
     * @return Battle
     */
    public function addBattleAttendance(BattleAttendance $battleAttendance)
    {
        $this->battleAttendances[] = $battleAttendance;

        return $this;
    }

    /**
     * Remove battleAttendance
     *
     * @param \AppBundle\Entity\BattleAttendance $battleAttendance
     */
    public function removeBattleAttendance(BattleAttendance $battleAttendance)
    {
        $this->battleAttendances->removeElement($battleAttendance);
    }

    /**
     * Get battleAttendances
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBattleAttendances()
    {
        return $this->battleAttendances;
    }

    /**
     * @return array
     */
    public function getBattleAttendancesPlayerIds()
    {
        $ret = [];
        foreach ($this->getBattleAttendances() as $att)
        {
            $ret[] = $att->getPlayer()->getId();
        }

        return $ret;
    }

    /**
     * @return array
     */
    public function getBattleReplayPlayerIds()
    {
        $ret = [];
        foreach ($this->getReplays() as $rep)
        {
            $ret[] = $rep->getPlayer()->getId();
        }

        return $ret;
    }

    /**
     * Add battleEnemyAttendance
     *
     * @param \AppBundle\Entity\BattleEnemyAttendance $battleEnemyAttendance
     *
     * @return Battle
     */
    public function addBattleEnemyAttendance(BattleEnemyAttendance $battleEnemyAttendance)
    {
        $this->battleEnemyAttendances[] = $battleEnemyAttendance;

        return $this;
    }

    /**
     * Remove battleEnemyAttendance
     *
     * @param \AppBundle\Entity\BattleEnemyAttendance $battleEnemyAttendance
     */
    public function removeBattleEnemyAttendance(BattleEnemyAttendance $battleEnemyAttendance)
    {
        $this->battleEnemyAttendances->removeElement($battleEnemyAttendance);
    }

    /**
     * Get battleEnemyAttendances
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBattleEnemyAttendances()
    {
        return $this->battleEnemyAttendances;
    }

    /**
     * Set isGoldPayed
     *
     * @param boolean $isGoldPayed
     *
     * @return Battle
     */
    public function setIsGoldPayed($isGoldPayed)
    {
        $this->isGoldPayed = $isGoldPayed;

        return $this;
    }

    /**
     * Get isGoldPayed
     *
     * @return boolean
     */
    public function getIsGoldPayed()
    {
        return $this->isGoldPayed;
    }

    /**
     * @param Player $player
     * @return Replay|null
     */
    public function getPlayerReplay(Player $player)
    {
        foreach ($this->getReplays() as $replay)
        {
            if ($replay->getPlayer()->getId() == $player->getId())
                return $replay;
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getCommanderName()
    {
        return $this->commanderName;
    }

    /**
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param string $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }


    /**
     * Set commanderName
     *
     * @param string $commanderName
     *
     * @return Battle
     */
    public function setCommanderName($commanderName)
    {
        $this->commanderName = $commanderName;

        return $this;
    }
}
