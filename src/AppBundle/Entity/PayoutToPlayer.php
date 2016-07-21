<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-07-18
 * Time: 22:59
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="payout_to_player")
 */
class PayoutToPlayer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Payout
     * @ORM\ManyToOne(targetEntity="Payout", inversedBy="payoutToPlayers")
     */
    private $payout;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Player")
     */
    private $player;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $playerName;

    /**
     * @ORM\Column(type="integer")
     */
    private $ptsFromCw = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $resourcesEarned = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalResourcesEarned = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isHqMember = false;

    /**
     * @ORM\Column(type="smallint")
     */
    private $noCommandedWin = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    private $noCommandedDraw = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    private $noCommandedLost = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    private $noPlayedWin = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    private $noPlayedDraw = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    private $noPlayedLost = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $goldFromCw = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $goldFromSh = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $goldFromHq = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $goldTotalPaid = 0;

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
     * Set ptsFromCw
     *
     * @param integer $ptsFromCw
     *
     * @return PayoutToPlayer
     */
    public function setPtsFromCw($ptsFromCw)
    {
        $this->ptsFromCw = $ptsFromCw;

        return $this;
    }

    /**
     * Get ptsFromCw
     *
     * @return integer
     */
    public function getPtsFromCw()
    {
        return $this->ptsFromCw;
    }

    /**
     * Set resourcesEarned
     *
     * @param integer $resourcesEarned
     *
     * @return PayoutToPlayer
     */
    public function setResourcesEarned($resourcesEarned)
    {
        $this->resourcesEarned = $resourcesEarned;

        return $this;
    }

    /**
     * Get resourcesEarned
     *
     * @return integer
     */
    public function getResourcesEarned()
    {
        return $this->resourcesEarned;
    }

    /**
     * Set isHqMember
     *
     * @param boolean $isHqMember
     *
     * @return PayoutToPlayer
     */
    public function setIsHqMember($isHqMember)
    {
        $this->isHqMember = $isHqMember;

        return $this;
    }

    /**
     * Get isHqMember
     *
     * @return boolean
     */
    public function getIsHqMember()
    {
        return $this->isHqMember;
    }

    /**
     * Set goldFromCw
     *
     * @param integer $goldFromCw
     *
     * @return PayoutToPlayer
     */
    public function setGoldFromCw($goldFromCw)
    {
        $this->goldFromCw = $goldFromCw;

        return $this;
    }

    /**
     * Get goldFromCw
     *
     * @return integer
     */
    public function getGoldFromCw()
    {
        return $this->goldFromCw;
    }

    /**
     * Set goldFromSh
     *
     * @param integer $goldFromSh
     *
     * @return PayoutToPlayer
     */
    public function setGoldFromSh($goldFromSh)
    {
        $this->goldFromSh = $goldFromSh;

        return $this;
    }

    /**
     * Get goldFromSh
     *
     * @return integer
     */
    public function getGoldFromSh()
    {
        return $this->goldFromSh;
    }

    /**
     * Set goldFromHq
     *
     * @param integer $goldFromHq
     *
     * @return PayoutToPlayer
     */
    public function setGoldFromHq($goldFromHq)
    {
        $this->goldFromHq = $goldFromHq;

        return $this;
    }

    /**
     * Get goldFromHq
     *
     * @return integer
     */
    public function getGoldFromHq()
    {
        return $this->goldFromHq;
    }

    /**
     * Set goldTotalPaid
     *
     * @param integer $goldTotalPaid
     *
     * @return PayoutToPlayer
     */
    public function setGoldTotalPaid($goldTotalPaid)
    {
        $this->goldTotalPaid = $goldTotalPaid;

        return $this;
    }

    /**
     * Get goldTotalPaid
     *
     * @return integer
     */
    public function getGoldTotalPaid()
    {
        return $this->goldTotalPaid;
    }

    /**
     * Set payout
     *
     * @param \AppBundle\Entity\Payout $payout
     *
     * @return PayoutToPlayer
     */
    public function setPayout(Payout $payout = null)
    {
        $this->payout = $payout;

        return $this;
    }

    /**
     * Get payout
     *
     * @return \AppBundle\Entity\Payout
     */
    public function getPayout()
    {
        return $this->payout;
    }

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return PayoutToPlayer
     */
    public function setPlayer(Player $player = null)
    {
        $this->player = $player;
        $this->playerName = $player->getUsername();

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
     * Set totalResourcesEarned
     *
     * @param integer $totalResourcesEarned
     *
     * @return PayoutToPlayer
     */
    public function setTotalResourcesEarned($totalResourcesEarned)
    {
        $this->totalResourcesEarned = $totalResourcesEarned;

        return $this;
    }

    /**
     * Get totalResourcesEarned
     *
     * @return integer
     */
    public function getTotalResourcesEarned()
    {
        return $this->totalResourcesEarned;
    }

    /**
     * Set noCommandedWin
     *
     * @param integer $noCommandedWin
     *
     * @return PayoutToPlayer
     */
    public function setNoCommandedWin($noCommandedWin)
    {
        $this->noCommandedWin = $noCommandedWin;

        return $this;
    }

    /**
     * Get noCommandedWin
     *
     * @return integer
     */
    public function getNoCommandedWin()
    {
        return $this->noCommandedWin;
    }

    /**
     * Set noCommandedDraw
     *
     * @param integer $noCommandedDraw
     *
     * @return PayoutToPlayer
     */
    public function setNoCommandedDraw($noCommandedDraw)
    {
        $this->noCommandedDraw = $noCommandedDraw;

        return $this;
    }

    /**
     * Get noCommandedDraw
     *
     * @return integer
     */
    public function getNoCommandedDraw()
    {
        return $this->noCommandedDraw;
    }

    /**
     * Set noCommandedLost
     *
     * @param integer $noCommandedLost
     *
     * @return PayoutToPlayer
     */
    public function setNoCommandedLost($noCommandedLost)
    {
        $this->noCommandedLost = $noCommandedLost;

        return $this;
    }

    /**
     * Get noCommandedLost
     *
     * @return integer
     */
    public function getNoCommandedLost()
    {
        return $this->noCommandedLost;
    }

    /**
     * Set noPlayedWin
     *
     * @param integer $noPlayedWin
     *
     * @return PayoutToPlayer
     */
    public function setNoPlayedWin($noPlayedWin)
    {
        $this->noPlayedWin = $noPlayedWin;

        return $this;
    }

    /**
     * Get noPlayedWin
     *
     * @return integer
     */
    public function getNoPlayedWin()
    {
        return $this->noPlayedWin;
    }

    /**
     * Set noPlayedDraw
     *
     * @param integer $noPlayedDraw
     *
     * @return PayoutToPlayer
     */
    public function setNoPlayedDraw($noPlayedDraw)
    {
        $this->noPlayedDraw = $noPlayedDraw;

        return $this;
    }

    /**
     * Get noPlayedDraw
     *
     * @return integer
     */
    public function getNoPlayedDraw()
    {
        return $this->noPlayedDraw;
    }

    /**
     * Set noPlayedLost
     *
     * @param integer $noPlayedLost
     *
     * @return PayoutToPlayer
     */
    public function setNoPlayedLost($noPlayedLost)
    {
        $this->noPlayedLost = $noPlayedLost;

        return $this;
    }

    /**
     * Get noPlayedLost
     *
     * @return integer
     */
    public function getNoPlayedLost()
    {
        return $this->noPlayedLost;
    }

    /**
     * Set playerName
     *
     * @param string $playerName
     *
     * @return PayoutToPlayer
     */
    private function setPlayerName($playerName)
    {
        $this->playerName = $playerName;

        return $this;
    }

    /**
     * Get playerName
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->playerName;
    }
}
