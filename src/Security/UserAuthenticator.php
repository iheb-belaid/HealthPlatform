<?php

namespace App\Security;

use App\Entity\Docteur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private UrlGeneratorInterface $urlGenerator;
     /** @var Symfony\Component\HttpFoundation\RequestStack $requestStack */
    private RequestStack $requestStack;

    public function __construct(UrlGeneratorInterface $urlGenerator, RequestStack $requestStack)
    {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
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
        /** @var \App\Entity\User $user */
        $user = $token->getUser();

        $session = $this->requestStack->getSession(); // Récupère la session active

        if ($user instanceof Docteur && !$user->isApproved()) {
            $session->getFlashBag()->add('error', 'Votre compte doit être approuvé par un administrateur avant de vous connecter.');
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }

        if (!$user instanceof UserInterface) {
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }

        // Rediriger en fonction du rôle de l'utilisateur
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
        } elseif (in_array('ROLE_DOCTEUR', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('doctor_dashboard'));
        } elseif (in_array('ROLE_PATIENT', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('patient_dashboard'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }

}