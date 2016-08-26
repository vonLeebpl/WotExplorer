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
    private $fame_points_since_turn;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fame_points_to_improve_award;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rank;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rank_delta;



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

    /**
     * Set famePointsSinceTurn
     *
     * @param integer $famePointsSinceTurn
     *
     * @return EventAccountData
     */
    public function setFamePointsSinceTurn($famePointsSinceTurn)
    {
        $this->fame_points_since_turn = $famePointsSinceTurn;

        return $this;
    }

    /**
     * Get famePointsSinceTurn
     *
     * @return integer
     */
    public function getFamePointsSinceTurn()
    {
        return $this->fame_points_since_turn;
    }

    /**
     * Set rankDelta
     *
     * @param integer $rankDelta
     *
     * @return EventAccountData
     */
    public function setRankDelta($rankDelta)
    {
        $this->rank_delta = $rankDelta;

        return $this;
    }

    /**
     * Get rankDelta
     *
     * @return integer
     */
    public function getRankDelta()
    {
        return $this->rank_delta;
    }

    public function parseFromArray($m)
    {
        //$this->setEvent(key($m));
        $m = $m[0];
        $this->setAwardLevel($m['award_level']);
        $this->setBattles($m['battles']);
        $this->setRank($m['rank']);
        $this->setBattlesToAward($m['battles_to_award']);
        $this->setFamePointsToImproveAward($m['fame_points_to_improve_award']);
        $this->setFamePoints($m['fame_points']);
        $this->setFamePointsSinceTurn($m['fame_points_since_turn']);
        $this->setRankDelta($m['rank_delta']);
        $this->setEvent($m['event_id']);
        $this->setAccountId($m['account_id']);
        
        return $this;
    }
}
