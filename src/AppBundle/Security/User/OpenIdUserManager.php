<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-04
 * Time: 12:23
 */

namespace AppBundle\Security\User;


use AppBundle\Entity\OpenIdIdentity;
use AppBundle\Entity\Player;
use AppBundle\Wot\WotApiWrapper;
use Doctrine\ORM\EntityManager;
use Fp\OpenIdBundle\Model\IdentityManagerInterface;
use Fp\OpenIdBundle\Model\UserManager;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

class OpenIdUserManager extends UserManager
{
    private $entityManager;
    private $security_settings;

    // we will use an EntityManager, so inject it via constructor
    public function __construct(IdentityManagerInterface $identityManager, EntityManager $entityManager, array $security_settings)
    {
        parent::__construct($identityManager);

        $this->entityManager = $entityManager;
        $this->security_settings = $security_settings;
    }

    /**
     * @param string $identity
     *  an OpenID token. With WOT it looks like:
     *  https://eu.wargaming.net/id/?account_id-nickname
     * @param array $attributes not working wit WGNET
     * @return \AppBundle\Entity\Player|void
     * @throws \Exception
     */
    public function createUserFromIdentity($identity, array $attributes = array())
    {
        // put your user creation logic here
        preg_match("/\d+/", $identity, $output);
        $accountId = $output[0];

        if (!is_numeric($accountId)) {
            throw new CustomUserMessageAuthenticationException("Couldn't retrieve your WOT ID from WG login request");
        }

        $wotapi = new WotApiWrapper();
        $ret = $wotapi->getClanMemberDetails($accountId);

        $clanTag = $ret[$accountId]['clan']['tag'];
        $nickname = $ret[$accountId]['account_name'];
        $position = $ret[$accountId]['role'];

        if (!in_array($clanTag, $this->security_settings['authorised_clans'])) {
            throw new CustomUserMessageAuthenticationException( "You are not a member of authorised clans for this site. Please contact admin.");
        }
        
        // 2) create user,
        // user could be loaded by clan sync
        $player = $this->findUserByUsername($nickname);

        if(!$player) {
            $player = $this->createUser();
            $player->setUsername($nickname);
            $player->setClan($clanTag);
            $player->setAccountId($accountId);
            $player->setEnabled(true);
            $player->setEmblem($ret[$accountId]['clan']['emblems']['x64']['portal']);
            $player->setPosition($ret[$accountId]['role_i18n']);
        }
        foreach ($this->security_settings['roles_matrix'] as $key => $value){
            if (in_array($position, $value) ){
                $player->addRole($key);
            }
        }
        if (in_array($nickname, $this->security_settings['super_admins'])) {
            $player->setSuperAdmin(true);
        }

        $this->updateUser($player);

        // what follows is a typical example
        $user = $this->findUserByUsername($nickname);

        if (null === $user) {
            throw new BadCredentialsException('No corresponding user!');
        }

        // we create an OpenIdIdentity for this User
        $openIdIdentity = new OpenIdIdentity();
        $openIdIdentity->setIdentity($identity);
        $openIdIdentity->setAttributes($attributes);
        $openIdIdentity->setUser($user);

        $this->entityManager->persist($openIdIdentity);
        $this->entityManager->flush();

        return $user; // you must return an UserInterface instance (or throw an exception)
    }

    /**
     * @param array $criteria
     * @return \AppBundle\Entity\Player
     */
    public function findUserBy( array $criteria )
    {
        return $this->entityManager
            ->getRepository( 'AppBundle:Player' )
            ->findOneBy( $criteria );
    }

    public function findUserByUsername( $username )
    {
        return $this->findUserBy( array( 'username' => $username ) );
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class instanceof Player;
    }

    /**
     * @param UserInterface $user
     * @return Player
     */
    public function refreshUser( UserInterface $user )
    {
        return $this->findUserBy( array( 'id' => $user->getId() ) );
    }

    /**
     * @param Player $user
     */
    public function updateUser( Player $user) {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @return Player
     */
    public function createUser()
    {
        return new Player();
    }

}