<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-27
 * Time: 19:53
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BattleEnemyAttendanceRepository")
 * @ORM\Table(name="battle_enemy_attendance")
 */
class BattleEnemyAttendance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Battle
     *
     * @ORM\ManyToOne(targetEntity="Battle", inversedBy="battleEnemyAttendances")
     */
    private $battle;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $tankShortName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAlive;

    /**
     * @ORM\Column(type="integer")
     */
    private $orygXp;

    /**
     * @ORM\Column(type="integer")
     */
    private $damage;

    /**
     * @ORM\Column(type="smallint")
     */
    private $frags;

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
     * Set name
     *
     * @param string $name
     *
     * @return BattleEnemyAttendance
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tankShortName
     *
     * @param string $tankShortName
     *
     * @return BattleEnemyAttendance
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
     * Set isAlive
     *
     * @param boolean $isAlive
     *
     * @return BattleEnemyAttendance
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

    /**
     * Set orygXp
     *
     * @param integer $orygXp
     *
     * @return BattleEnemyAttendance
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
     * @return BattleEnemyAttendance
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
     * Set frags
     *
     * @param integer $frags
     *
     * @return BattleEnemyAttendance
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
     * Set battle
     *
     * @param \AppBundle\Entity\Battle $battle
     *
     * @return BattleEnemyAttendance
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
}
