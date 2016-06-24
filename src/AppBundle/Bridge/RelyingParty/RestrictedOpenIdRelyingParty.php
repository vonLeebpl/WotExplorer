<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-23
 * Time: 16:51
 */

namespace AppBundle\Bridge\RelyingParty;


use Fp\OpenIdBundle\Bridge\RelyingParty\LightOpenIdRelyingParty;
use Fp\OpenIdBundle\RelyingParty\Exception\OpenIdAuthenticationValidationFailedException;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;

class RestrictedOpenIdRelyingParty extends LightOpenIdRelyingParty
{
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    protected function guessIdentifier(Request $request)
    {
        foreach ($this->container->getParameter('valid_openid_providers') as $provider) {
            $providers[] = $provider['url'];
        }

        if(in_array($request->get('openid_identifier'), $providers)) {
            return $request->get('openid_identifier');
        } else {
            throw new OpenIdAuthenticationValidationFailedException("Invalid OpenID provider used", 1);
        }
    }
}