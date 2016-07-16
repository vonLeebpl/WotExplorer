<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-01
 * Time: 23:46
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Player used as user for User Provider
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayerRepository")
 */
class Player implements AdvancedUserInterface, \Serializable
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BattleAttendance", mappedBy="player", cascade={"remove"})
     */
    private $battleAttendances;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Replay", mappedBy="player", cascade={"remove"})
     */
    private $cwReplays;

    /**
     * @var integer WOT AccountId
     * @ORM\Column(type="integer", unique=true)
     */
    private $accountId;

    /**
     * @var string
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $clan;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $locked;

    /**
     *
     * @ORM\Column(name="last_login",type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $emblem;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $position;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at",type="datetime")
     */
    protected $updatedAt;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at",type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $totalResourcesEarned;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $weekResourcesEarned;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $lastPayoutResources;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $resourcesToPayout;

    public function __construct()
    {
        $this->enabled = false;
        $this->locked = false;
        $this->roles = array();
        $this->totalResourcesEarned = 0;
        $this->lastPayoutResources = 0;
        $this->resourcesToPayout = 0;
        
        $this->battleAttendances = new ArrayCollection();
        $this->cwReplays = new ArrayCollection();
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
     * Set accountId
     *
     * @param integer $accountId
     *
     * @return Player
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
     * Set clan
     *
     * @param string $clan
     *
     * @return Player
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
     * Tells if the the given user is this user.
     *
     * Useful when not hydrating all fields.
     *
     * @param AdvancedUserInterface $user
     *
     * @return Boolean
     */
    public function isUser( AdvancedUserInterface $user = null )
    {
        return $this->getId() == $user->getId();
    }

    /**
     * Sets the username.
     *
     * @param string $username
     * @return void|static
     */
    public function setUsername( $username )
    {
        $this->username = $username;
    }
    /**
     * Gets the username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return Boolean true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param Boolean $enable
     * @return void|static
     */
    public function setEnabled( $enable )
    {
        $this->enabled = $enable;
    }
    /**
     * Get locked
     *
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Sets the locking status of the user.
     *
     * @param Boolean $lock
     *
     * @return $this
     */
    public function setLocked( $lock )
    {
        $this->locked = $lock;

        return $this;
    }
    /**
     * Get lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Sets the last login time
     *
     * @param \DateTime $time
     * @return $this
     */
    public function setLastLogin( \DateTime $time = NULL )
    {
        $this->lastLogin = $time;

        return $this;
    }
    /**
     * Tells if the the given user has the super admin role.
     *
     * @return Boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasRole( 'ROLE_SUPER_ADMIN' );
    }

    /**
     * Sets the super admin status
     *
     * @param Boolean $setSuperAdmin
     * @return $this
     */
    public function setSuperAdmin( $setSuperAdmin )
    {
        if ( $setSuperAdmin )
        {
            $this->addRole( 'ROLE_SUPER_ADMIN' );
        }
        else
        {
            $this->removeRole( 'ROLE_SUPER_ADMIN' );
        }

        return $this;
    }
    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return Boolean
     */
    public function hasRole( $role )
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    /**
     * Sets the roles of the user.
     *
     * This overwrites any previous roles.
     *
     * @param array $roles
     * @return $this
     */
    public function setRoles( array $roles )
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Adds a role to the user.
     *
     * @param string $role
     * @return $this|static
     */
    public function addRole( $role )
    {
        if ( !$this->hasRole( $role ) )
        {
            $this->roles[] = strtoupper( $role );
        }
        return $this;
    }

    /**
     * Removes a role to the user.
     *
     * @param string $role
     * @return $this|static
     */
    public function removeRole( $role )
    {
        if ( false !== $key = array_search(strtoupper( $role ), $this->roles, true ) )
        {
            unset( $this->roles[$key] );
            $this->roles = array_values( $this->roles );
        }
        return $this;
    }

    /**
     * Returns the roles of the user.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }


    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->accountId,
            $this->locked,
            $this->enabled,
            $this->roles,
        ));
    }
    /**
     * Unserializes the user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));
        list(
            $this->id,
            $this->username,
            $this->accountId,
            $this->locked,
            $this->enabled,
            $this->roles
            ) = $data;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return Boolean true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }
    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return Boolean true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return !$this->locked;
    }
    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return Boolean true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }
    /**
     * Not implemented
     */
    public function eraseCredentials(){}

    /**
     * Not implemented
     */
    public function getPassword(){}

    /**
     * @param $password
     */
    public function setPassword($password){}
    /**
     * Not implemented
     */
    public function getSalt(){}
    /**
     * Not implemented
     */

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return $this
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return $this
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
     * Add battleAttendance
     *
     * @param \AppBundle\Entity\BattleAttendance $battleAttendance
     *
     * @return Player
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
     * Add cwReplay
     *
     * @param \AppBundle\Entity\Replay $cwReplay
     *
     * @return Player
     */
    public function addCwReplay(Replay $cwReplay)
    {
        $this->cwReplays[] = $cwReplay;

        return $this;
    }

    /**
     * Remove cwReplay
     *
     * @param \AppBundle\Entity\Replay $cwReplay
     */
    public function removeCwReplay(Replay $cwReplay)
    {
        $this->cwReplays->removeElement($cwReplay);
    }

    /**
     * Get cwReplays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCwReplays()
    {
        return $this->cwReplays;
    }

    /**
     * @param AdvancedUserInterface $user
     * @return bool
     */
    public function isEqualTo(AdvancedUserInterface $user)
    {
        if (!$user instanceof Player) {
            return false;
        }

        if ($this->accountId !== $user->getAccountId()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * Set emblem
     *
     * @param string $emblem
     *
     * @return Player
     */
    public function setEmblem($emblem)
    {
        $this->emblem = $emblem;

        return $this;
    }

    /**
     * Get emblem
     *
     * @return string
     */
    public function getEmblem()
    {
        return $this->emblem;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return Player
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set totalResourcesEarned
     *
     * @param integer $totalResourcesEarned
     *
     * @return Player
     */
    public function setTotalResourcesEarned($totalResourcesEarned)
    {
        $this->totalResourcesEarned = $totalResourcesEarned;
        $this->resourcesToPayout = $totalResourcesEarned - $this->lastPayoutResources;

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
     * Set weekResourcesEarned
     *
     * @param integer $weekResourcesEarned
     *
     * @return Player
     */
    public function setWeekResourcesEarned($weekResourcesEarned)
    {
        $this->weekResourcesEarned = $weekResourcesEarned;

        return $this;
    }

    /**
     * Get weekResourcesEarned
     *
     * @return integer
     */
    public function getWeekResourcesEarned()
    {
        return $this->weekResourcesEarned;
    }

    /**
     * Set lastPayoutResources
     *
     * @param integer $lastPayoutResources
     *
     * @return Player
     */
    public function setLastPayoutResources($lastPayoutResources)
    {
        $this->lastPayoutResources = $lastPayoutResources;
        $this->resourcesToPayout = $this->totalResourcesEarned - $lastPayoutResources;

        return $this;
    }

    /**
     * Get lastPayoutResources
     *
     * @return integer
     */
    public function getLastPayoutResources()
    {
        return $this->lastPayoutResources;
    }

    /**
     * Set resourcesToPayout
     *
     * @param integer $resourcesToPayout
     *
     * @return Player
     */
    public function setResourcesToPayout($resourcesToPayout)
    {
        $this->resourcesToPayout = $resourcesToPayout;

        return $this;
    }

    /**
     * Get resourcesToPayout
     *
     * @return integer
     */
    public function getResourcesToPayout()
    {
        return $this->resourcesToPayout;
    }
}
