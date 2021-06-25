<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginFormAuthenticator extends AbstractGuardAuthenticator
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'security_login'
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        return $request->request->get('login'); //Array(=tableau) avec 3 infos (Je veux être authentifier, je sors mes infos de la request)
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {

            return $userProvider->loadUserByUsername($credentials['email']); // Va chercher l'utilisateur qui correspond à cet email. Le provider cherche grace à (security.yaml), et on trouve l'email dans la bdd
        } catch (UsernameNotFoundException $e) {

            throw new AuthenticationException("Cette adrese email n'est pas connue");
        }
    }

    public function checkCredentials($credentials, UserInterface $user) // Vérifier que le mot de passe fourni, correspond à celui de l'utilisateur trouvé
    {
        // vérifier que $user->getPassword()(mot de passe que l'user a entré) correspond bien à $credentials['password'](le mdp dans la bdd)
        $isValid = $this->encoder->isPasswordValid($user, $credentials['password']);

        if (!$isValid) {
            throw new AuthenticationException("Le mot de passe de connexion ne correspond pas.");
        }
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->attributes->set(Security::AUTHENTICATION_ERROR, $exception);
        $login = $request->request->get('login');
        $request->attributes->set(Security::LAST_USERNAME, $login['email']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse('/');
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/login');
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
