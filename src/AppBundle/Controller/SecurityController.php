<?php

namespace AppBundle\Controller;

use AppBundle\Wot\WotApiWrapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Fp\OpenIdBundle\RelyingParty\RecoveredFailureRelyingParty;
use Fp\OpenIdBundle\RelyingParty\Exception\OpenIdAuthenticationCanceledException;
use Fp\OpenIdBundle\Security\Core\Authentication\Token\OpenIdToken;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/finish_login_openid")
     */
    public function finishOpenIdLoginAction(Request $request)
    {
        //AuthenticationException or its child
        $helper = $this->get('security.authentication_utils');
        $failure = $helper->getLastAuthenticationError();
        if (false == $failure) {
            throw new \LogicException('The controller expect AuthenticationException to be present in session');
        }
        if ($failure instanceof OpenIdAuthenticationCanceledException) {
            // do some action on cancel. Add a flash message etc.

            return $this->redirect('/');
        }

        /**
         * @var $token OpenIdToken
         */
        $token = $failure->getToken();
        if (false == $token instanceof OpenIdToken) {
            throw new \LogicException('The exception do not contain OpenIdToken, does it come from openid?');
        }
        //you have to do:
        // 1) check if user belongs to authorised clan
        $authClans = $this->getParameter('authorised_clans');
        $identity = $token->getIdentity();

        preg_match("/\d+/", $identity, $output);
        $accountId = $output[0];

        if (!is_numeric($accountId)) {
            $this->addFlash('error', "Couldn't retrieve your WOT ID from WG login request");
            return $this->redirect('/');
        }

        $wotapi = new WotApiWrapper();
        $ret = $wotapi->getClanMemberDetails($accountId);

        $clanTag = $ret[$accountId]['clan']['tag'];
        $nickname = $ret[$accountId]['account_name'];

        if (!in_array($clanTag, $authClans)) {
            $this->addFlash('error', "You are not a member of authorised clans for this site. Please contact admin.");
            return $this->redirect('/');
        }

        // 2) create user or update last login,
        // user could be loaded by clan sync
        $umgr = $this->getUserManager();
        $player = $umgr->findUserByUsername($nickname);

        if(!$player) {
            $player = $umgr->createUser();
            $player->setUsername($nickname);
            $player->setClan($clanTag);
            $player->setAccountId($accountId);
        }
        $player->setLastLogin(new \DateTime('now'));
        $umgr->updateUser($player);

        // 2) create identity
        $id = $this->getIdentityManager()->create();
        $id->setIdentity($identity);
        $id->setUser($player);
        $id->setAttributes([]);

        $this->getIdentityManager()->update($id);

        //when you are done you can finish authentication process.
        return $this->redirect($this->generateUrl('fp_openid_security_check', array(
            RecoveredFailureRelyingParty::RECOVERED_QUERY_PARAMETER => 1
        )));
    }
    protected function getIdentityManager()
    {
        return $this->get('fp_openid.identity_manager');
    }

    protected function getUserManager()
    {
        return $this->get('app.security_user.open_id_user_manager');
    }
}
