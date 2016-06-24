<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-04-12
 * Time: 20:17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventAccountDataRepository")
 * @ORM\Table(name="eventaccountdata")
 */
class EventAccountData
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Clan", inversedBy="event_accounts_info")
     * @ORM\JoinColumn(name="clan_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $clan;


    /**
     * @ORM\Column(name="event_id", type="string", nullable=true)
     */
    private $event;

    /**
     * @ORM\Column(type="integer")
     */
    private $accountId;

    /**
     * @ORM\Column(type="string")
     */
    private $accountName;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $joined_at;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $award_level;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $battles;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $battles_to_award;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fame_points;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fame_points_to_improve_award;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rank;

    private $days_in_clan;



    /*
     * get days_in_clan
     */
    public function getDaysInClan()
    {
        $t = new \DateTime('now');
        return $t->diff($this->getJoinedAt())->format('%a');
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
     * Set event
     *
     * @param string $event
     * @return EventAccountData
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return string 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set accountId
     *
     * @param integer $accountId
     * @return EventAccountData
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get accountId
     *
     * @return integer 
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Set accountName
     *
     * @param string $accountName
     * @return EventAccountData
     */
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;

        return $this;
    }

    /**
     * Get accountName
     *
     * @return string 
     */
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * Set joined_at
     *
     * @param \DateTime $joinedAt
     * @return EventAccountData
     */
    public function setJoinedAt($joinedAt)
    {
        $this->joined_at = $joinedAt;

        return $this;
    }

    /**
     * Get joined_at
     *
     * @return \DateTime 
     */
    public function getJoinedAt()
    {
        return $this->joined_at;
    }

    /**
     * Set award_level
     *
     * @param string $awardLevel
     * @return EventAccountData
     */
    public function setAwardLevel($awardLevel)
    {
        $this->award_level = $awardLevel;

        return $this;
    }

    /**
     * Get award_level
     *
     * @return string 
     */
    public function getAwardLevel()
    {
        return $this->award_level;
    }

    /**
     * Set battles
     *
     * @param integer $battles
     * @return EventAccountData
     */
    public function setBattles($battles)
    {
        $this->battles = $battles;

        return $this;
    }

    /**
     * Get battles
     *
     * @return integer 
     */
    public function getBattles()
    {
        return $this->battles;
    }

    /**
     * Set battles_to_award
     *
     * @param integer $battlesToAward
     * @return EventAccountData
     */
    public function setBattlesToAward($battlesToAward)
    {
        $this->battles_to_award = $battlesToAward;

        return $this;
    }

    /**
     * Get battles_to_award
     *
     * @return integer 
     */
    public function getBattlesToAward()
    {
        return $this->battles_to_award;
    }

    /**
     * Set fame_points
     *
     * @param integer $famePoints
     * @return EventAccountData
     */
    public function setFamePoints($famePoints)
    {
        $this->fame_points = $famePoints;

        return $this;
    }

    /**
     * Get fame_points
     *
     * @return integer 
     */
    public function getFamePoints()
    {
        return $this->fame_points;
    }

    /**
     * Set fame_points_to_improve_award
     *
     * @param integer $famePointsToImproveAward
     * @return EventAccountData
     */
    public function setFamePointsToImproveAward($famePointsToImproveAward)
    {
        $this->fame_points_to_improve_award = $famePointsToImproveAward;

        return $this;
    }

    /**
     * Get fame_points_to_improve_award
     *
     * @return integer 
     */
    public function getFamePointsToImproveAward()
    {
        return $this->fame_points_to_improve_award;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return EventAccountData
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set clan
     *
     * @param \AppBundle\Entity\Clan $clan
     * @return EventAccountData
     */
    public function setClan(\AppBundle\Entity\Clan $clan = null)
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
