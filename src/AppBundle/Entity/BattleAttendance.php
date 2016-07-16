<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-01
 * Time: 23:40
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Battle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BattleAttendanceRepository")
 */
class BattleAttendance
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
     * @var Battle
     *
     * @ORM\ManyToOne(targetEntity="Battle", inversedBy="battleAttendances")
     */
    private $battle;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Player", inversedBy="battleAttendances")
     */
    private $player;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAlive;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $orygXp;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $damage;

    /**
     * @ORM\Column(type="smallint")
     */
    private $frags = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $tankId;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $tankShortName;

    /**
     * @ORM\Column(type="integer")
     */
    private $resourceAbsorbed = 0;

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
     * Set orygXp
     *
     * @param integer $orygXp
     *
     * @return BattleAttendance
     */
    public function setOrygXp($orygXp)
    {
        $this->orygXp = $orygXp;

        return $this;
    }

    /**
     * Get orygXp
     *
     * @return integer
     */
    public function getOrygXp()
    {
        return $this->orygXp;
    }

    /**
     * Set damage
     *
     * @param integer $damage
     *
     * @return BattleAttendance
     */
    public function setDamage($damage)
    {
        $this->damage = $damage;

        return $this;
    }

    /**
     * Get damage
     *
     * @return integer
     */
    public function getDamage()
    {
        return $this->damage;
    }

    /**
     * Set battle
     *
     * @param \AppBundle\Entity\Battle $battle
     *
     * @return BattleAttendance
     */
    public function setBattle(Battle $battle = null)
    {
        $this->battle = $battle;

        return $this;
    }

    /**
     * Get battle
     *
     * @return \AppBundle\Entity\Battle
     */
    public function getBattle()
    {
        return $this->battle;
    }

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return BattleAttendance
     */
    public function setPlayer(Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set resourceAbsorbed
     *
     * @param integer $resourceAbsorbed
     *
     * @return BattleAttendance
     */
    public function setResourceAbsorbed($resourceAbsorbed)
    {
        $this->resourceAbsorbed = $resourceAbsorbed;

        return $this;
    }

    /**
     * Get resourceAbsorbed
     *
     * @return integer
     */
    public function getResourceAbsorbed()
    {
        return $this->resourceAbsorbed;
    }

    /**
     * Set tankId
     *
     * @param integer $tankId
     *
     * @return BattleAttendance
     */
    public function setTankId($tankId)
    {
        $this->tankId = $tankId;

        return $this;
    }

    /**
     * Get tankId
     *
     * @return integer
     */
    public function getTankId()
    {
        return $this->tankId;
    }

    /**
     * Set tankShortName
     *
     * @param string $tankShortName
     *
     * @return BattleAttendance
     */
    public function setTankShortName($tankShortName)
    {
        $this->tankShortName = $tankShortName;

        return $this;
    }

    /**
     * Get tankShortName
     *
     * @return string
     */
    public function getTankShortName()
    {
        return $this->tankShortName;
    }

    /**
     * Set frags
     *
     * @param integer $frags
     *
     * @return BattleAttendance
     */
    public function setFrags($frags)
    {
        $this->frags = $frags;

        return $this;
    }

    /**
     * Get frags
     *
     * @return integer
     */
    public function getFrags()
    {
        return $this->frags;
    }

    /**
     * Set isAlive
     *
     * @param boolean $isAlive
     *
     * @return BattleAttendance
     */
    public function setIsAlive($isAlive)
    {
        $this->isAlive = $isAlive;

        return $this;
    }

    /**
     * Get isAlive
     *
     * @return boolean
     */
    public function getIsAlive()
    {
        return $this->isAlive;
    }
}
