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
    public function setBattle(\AppBundle\Entity\Battle $battle = null)
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
    public function setPlayer(\AppBundle\Entity\Player $player = null)
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
}
