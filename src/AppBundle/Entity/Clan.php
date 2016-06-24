<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-04-11
 * Time: 22:42
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClanRepository")
 * @ORM\HasLifeCycleCallbacks()
 * @ORM\Table(name="clan")
 */
class Clan
{
    /**
     * @ORM\OneToMany(targetEntity="EventAccountData", mappedBy="clan", cascade={"persist", "remove"})
     */
    private $event_accounts_info;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     *
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", name="accepts_join_requests")
     */
    private $acceptsJoinRequests = 1;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", name="creator_id")
     */
    private $creatorId;

    /**
     * @ORM\Column(type="string", name="creator_name")
     */
    private $creatorName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", name="description_html", nullable=true)
     */
    private $descriptionHtml;

    /**
     * @ORM\Column(type="boolean", name="is_clan_disbanded")
     */
    private $isClanDisbanded = 0;

    /**
     * @ORM\Column(type="integer", name="leader_id")
     */
    private $leaderId;

    /**
     * @ORM\Column(type="string", name="leader_name")
     */
    private $leaderName;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $tag;

    /**
     * @ORM\Column(type="integer", name="members_count")
     */
    private $membersCount;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $motto;

    /**
     * @ORM\Column(type="string", name="old_name", nullable=true)
     */
    private $oldName;

    /**
     * @ORM\Column(type="string", name="old_tag", nullable=true)
     */
    private $oldTag;

    /**
     * @ORM\Column(type="datetime", name="renamed_at", nullable=true)
     * @Assert\DateTime()
     */
    private $renamedAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     * @Assert\DateTime()
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $emblems;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $members;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $membersIds;

    /**
     * @ORM\Column(type="datetime", name="last_refreshed", nullable= true)
     * @Assert\DateTime()
     */
    private $lastRefreshed;

    /**
     * @ORM\Column(type="datetime", name="last_event_update", nullable= true)
     * @Assert\DateTime()
     */
    private $lastEventUpdateDate;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->event_accounts_info = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Clan
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set name
     *
     * @param string $name
     * @return Clan
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
     * Set tag
     *
     * @param string $tag
     * @return Clan
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set members_count
     *
     * @param integer $membersCount
     * @return Clan
     */
    public function setMembersCount($membersCount)
    {
        $this->membersCount = $membersCount;

        return $this;
    }

    /**
     * Get members_count
     *
     * @return integer 
     */
    public function getMembersCount()
    {
        return $this->membersCount;
    }

    /**
     * Set lastUpdated
     *
     * @param \DateTime $lastRefreshed
     * @return Clan
     */
    public function setLastRefreshed(\DateTime $lastRefreshed)
    {
        $this->lastRefreshed = $lastRefreshed;

        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return \DateTime 
     */
    public function getLastRefreshed()
    {
        return $this->lastRefreshed;
    }

    /**
     * Set lastEventUpdateDate
     *
     * @param \DateTime $lastEventUpdateDate
     * @return Clan
     */
    public function setLastEventUpdateDate(\DateTime $lastEventUpdateDate)
    {
        $this->lastEventUpdateDate = $lastEventUpdateDate;

        return $this;
    }

    /**
     * Get lastEventUpdateDate
     *
     * @return \DateTime 
     */
    public function getLastEventUpdateDate()
    {
        return $this->lastEventUpdateDate;
    }

    /**
     * Add members
     *
     * @param \AppBundle\Entity\EventAccountData $members
     * @return Clan
     */
    public function addMember(\AppBundle\Entity\EventAccountData $members)
    {
        $this->event_accounts_info[] = $members;

        return $this;
    }

    /**
     * Remove members
     *
     * @param \AppBundle\Entity\EventAccountData $members
     */
    public function removeMember(\AppBundle\Entity\EventAccountData $members)
    {
        $this->event_accounts_info->removeElement($members);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEventAccountsInfo()
    {
        return $this->event_accounts_info;
    }

    /**
     * Set acceptsJoinRequests
     *
     * @param boolean $acceptsJoinRequests
     * @return Clan
     */
    public function setAcceptsJoinRequests($acceptsJoinRequests)
    {
        $this->acceptsJoinRequests = $acceptsJoinRequests;

        return $this;
    }

    /**
     * Get acceptsJoinRequests
     *
     * @return boolean 
     */
    public function getAcceptsJoinRequests()
    {
        return $this->acceptsJoinRequests;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Clan
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Clan
     */
    public function setCreatedAt(\DateTime $createdAt)
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
     * Set creatorId
     *
     * @param integer $creatorId
     * @return Clan
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
     * Set creatorName
     *
     * @param string $creatorName
     * @return Clan
     */
    public function setCreatorName($creatorName)
    {
        $this->creatorName = $creatorName;

        return $this;
    }

    /**
     * Get creatorName
     *
     * @return string 
     */
    public function getCreatorName()
    {
        return $this->creatorName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Clan
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
     * Set descriptionHtml
     *
     * @param string $descriptionHtml
     * @return Clan
     */
    public function setDescriptionHtml($descriptionHtml)
    {
        $this->descriptionHtml = $descriptionHtml;

        return $this;
    }

    /**
     * Get descriptionHtml
     *
     * @return string 
     */
    public function getDescriptionHtml()
    {
        return $this->descriptionHtml;
    }

    /**
     * Set isClanDisbanded
     *
     * @param boolean $isClanDisbanded
     * @return Clan
     */
    public function setIsClanDisbanded($isClanDisbanded)
    {
        $this->isClanDisbanded = $isClanDisbanded;

        return $this;
    }

    /**
     * Get isClanDisbanded
     *
     * @return boolean 
     */
    public function getIsClanDisbanded()
    {
        return $this->isClanDisbanded;
    }

    /**
     * Set leaderId
     *
     * @param integer $leaderId
     * @return Clan
     */
    public function setLeaderId($leaderId)
    {
        $this->leaderId = $leaderId;

        return $this;
    }

    /**
     * Get leaderId
     *
     * @return integer 
     */
    public function getLeaderId()
    {
        return $this->leaderId;
    }

    /**
     * Set leaderName
     *
     * @param string $leaderName
     * @return Clan
     */
    public function setLeaderName($leaderName)
    {
        $this->leaderName = $leaderName;

        return $this;
    }

    /**
     * Get leaderName
     *
     * @return string 
     */
    public function getLeaderName()
    {
        return $this->leaderName;
    }

    /**
     * Set motto
     *
     * @param string $motto
     * @return Clan
     */
    public function setMotto($motto)
    {
        $this->motto = $motto;

        return $this;
    }

    /**
     * Get motto
     *
     * @return string 
     */
    public function getMotto()
    {
        return $this->motto;
    }

    /**
     * Set oldName
     *
     * @param string $oldName
     * @return Clan
     */
    public function setOldName($oldName)
    {
        $this->oldName = $oldName;

        return $this;
    }

    /**
     * Get oldName
     *
     * @return string 
     */
    public function getOldName()
    {
        return $this->oldName;
    }

    /**
     * Set oldTag
     *
     * @param string $oldTag
     * @return Clan
     */
    public function setOldTag($oldTag)
    {
        $this->oldTag = $oldTag;

        return $this;
    }

    /**
     * Get oldTag
     *
     * @return string 
     */
    public function getOldTag()
    {
        return $this->oldTag;
    }

    /**
     * Set renamedAt
     *
     * @param \DateTime $renamedAt
     * @return Clan
     */
    public function setRenamedAt(\DateTime $renamedAt)
    {
        $this->renamedAt = $renamedAt;

        return $this;
    }

    /**
     * Get renamedAt
     *
     * @return \DateTime 
     */
    public function getRenamedAt()
    {
        return $this->renamedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Clan
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set emblems
     *
     * @param array $emblems
     * @return Clan
     */
    public function setEmblems($emblems)
    {
        $this->emblems = $emblems;

        return $this;
    }

    /**
     * Get emblems
     *
     * @return array 
     */
    public function getEmblems()
    {
        return $this->emblems;
    }

    /**
     * Set members
     *
     * @param array $members
     * @return Clan
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    /**
     * Get members
     *
     * @return array 
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set membersIds
     *
     * @param array $membersIds
     * @return Clan
     */
    public function setMembersIds($membersIds)
    {
        $this->membersIds = $membersIds;

        return $this;
    }

    /**
     * Get membersIds
     *
     * @return array 
     */
    public function getMembersIds()
    {
        return $this->membersIds;
    }

    /**
     * Add event_accounts_info
     *
     * @param \AppBundle\Entity\EventAccountData $eventAccountsInfo
     * @return Clan
     */
    public function addEventAccountsInfo(\AppBundle\Entity\EventAccountData $eventAccountsInfo)
    {
        $this->event_accounts_info[] = $eventAccountsInfo;

        return $this;
    }

    /**
     * Remove event_accounts_info
     *
     * @param \AppBundle\Entity\EventAccountData $eventAccountsInfo
     */
    public function removeEventAccountsInfo(\AppBundle\Entity\EventAccountData $eventAccountsInfo)
    {
        $this->event_accounts_info->removeElement($eventAccountsInfo);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function onPrePersistPreUpdate()
    {
        //TODO: update lastRefresh, remove clan_id from referenced tables

        // building membersIds array
        $arr = [];
        foreach ($this->getMembers() as $member) {
            $arr[] = $member['account_id'];
        }
        $this->setMembersIds($arr);
    }
}
