<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class UserAuthenticator extends AbstractLoginFormAuthenticator implements AuthenticationEntryPointInterface
{
    use TargetPathTrait;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
{
    $user = $token->getUser();

    // Affichage des rôles pour déboguer
    // Vérifier si "ROLE_DOCTEUR" ou "ROLE_DOCTOR" est affiché

    if (!$user instanceof UserInterface) {
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    // Rediriger en fonction du rôle de l'utilisateur
    if (in_array('ROLE_ADMIN', $user->getRoles())) {
        return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
    } elseif (in_array('ROLE_DOCTEUR', $user->getRoles())) { // Utilise "ROLE_DOCTEUR" ici
        return new RedirectResponse($this->urlGenerator->generate('doctor_dashboard'));
    } elseif (in_array('ROLE_PATIENT', $user->getRoles())) {
        return new RedirectResponse($this->urlGenerator->generate('patient_dashboard'));
    }

    // Redirection par défaut si aucun rôle n'est trouvé
    return new RedirectResponse($this->urlGenerator->generate('app_home'));
}


    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }
}
