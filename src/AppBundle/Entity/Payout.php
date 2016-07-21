<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-07-16
 * Time: 16:06
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PayoutRepository")
 * @ORM\Table(name="payout")
 * @ORM\HasLifecycleCallbacks()
 */
class Payout
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\PayoutConfigUsedByPayout", mappedBy="payout", cascade={"remove"})
     */
    private $config;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Clan")
     */
    private $clan;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PayoutToPlayer", mappedBy="payout", cascade={"remove"})
     */
    private $payoutToPlayers;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCompleted = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $completedAt;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Player")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $completedBy;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalGoldToPay = 0;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $battles;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function __construct()
    {
        $this->payoutToPlayers = new ArrayCollection();
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Payout
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
     * Set isCompleted
     *
     * @param boolean $isCompleted
     *
     * @return Payout
     */
    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * Get isCompleted
     *
     * @return boolean
     */
    public function getIsCompleted()
    {
        return $this->isCompleted;
    }

    /**
     * Set completedAt
     *
     * @param \DateTime $completedAt
     *
     * @return Payout
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    /**
     * Get completedAt
     *
     * @return \DateTime
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Payout
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set totalGoldToPay
     *
     * @param integer $totalGoldToPay
     *
     * @return Payout
     */
    public function setTotalGoldToPay($totalGoldToPay)
    {
        $this->totalGoldToPay = $totalGoldToPay;

        return $this;
    }

    /**
     * Get totalGoldToPay
     *
     * @return integer
     */
    public function getTotalGoldToPay()
    {
        return $this->totalGoldToPay;
    }

    /**
     * Set battles
     *
     * @param array $battles
     *
     * @return Payout
     */
    public function setBattles($battles)
    {
        $this->battles = $battles;

        return $this;
    }

    /**
     * Get battles
     *
     * @return array
     */
    public function getBattles()
    {
        return $this->battles;
    }

    /**
     * Set config
     *
     * @param \AppBundle\Entity\PayoutConfigUsedByPayout $config
     *
     * @return Payout
     */
    public function setConfig(PayoutConfigUsedByPayout $config = null)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get config
     *
     * @return \AppBundle\Entity\PayoutConfigUsedByPayout
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Add payoutToPlayer
     *
     * @param \AppBundle\Entity\PayoutToPlayer $payoutToPlayer
     *
     * @return Payout
     */
    public function addPayoutToPlayer(PayoutToPlayer $payoutToPlayer)
    {
        $this->payoutToPlayers[] = $payoutToPlayer;

        return $this;
    }

    /**
     * Remove payoutToPlayer
     *
     * @param \AppBundle\Entity\PayoutToPlayer $payoutToPlayer
     */
    public function removePayoutToPlayer(PayoutToPlayer $payoutToPlayer)
    {
        $this->payoutToPlayers->removeElement($payoutToPlayer);
    }

    /**
     * Get payoutToPlayers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayoutToPlayers()
    {
        return $this->payoutToPlayers;
    }

    /**
     * Set completedBy
     *
     * @param \AppBundle\Entity\Player $completedBy
     *
     * @return Payout
     */
    public function setCompletedBy(Player $completedBy = null)
    {
        $this->completedBy = $completedBy;

        return $this;
    }

    /**
     * Get completedBy
     *
     * @return \AppBundle\Entity\Player
     */
    public function getCompletedBy()
    {
        return $this->completedBy;
    }

    /**
     * Set clan
     *
     * @param \AppBundle\Entity\Clan $clan
     *
     * @return Payout
     */
    public function setClan(Clan $clan = null)
    {
        $this->clan = $clan;

        return $this;
    }

    /**
     * Get clan
     *
     * @return \AppBundle\Entity\Clan
     */
    public function getClan()
    {
        return $this->clan;
    }
}
