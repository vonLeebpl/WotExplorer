<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-07
 * Time: 19:08
 */

namespace AppBundle\Listener;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticatedEventListener
{
    protected $objectManager;

    public function __construct( ObjectManager $objectManager )
    {
        $this->objectManager = $objectManager;
    }

    public function onAuthenticationSuccess( AuthenticationEvent $event )
    {
        $user = $event->getAuthenticationToken()->getUser();
        if ( $user instanceof UserInterface )
        {
            $user->setLastLogin( new \DateTime('now') );
            $this->objectManager->flush();
        }
    }
}