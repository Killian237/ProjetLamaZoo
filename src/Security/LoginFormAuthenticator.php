<?php

namespace App\Security;

use App\Service\LoginAttemptService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    private $loginAttemptService;
    private $router;

    public function __construct(LoginAttemptService $loginAttemptService, RouterInterface $router)
    {
        $this->loginAttemptService = $loginAttemptService;
        $this->router = $router;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');
        $csrfToken = $request->request->get('_csrf_token');
        $identifier = $email . $request->getClientIp();

        if ($this->loginAttemptService->isBlocked($identifier)) {
            $remaining = $this->loginAttemptService->getRemainingTime($identifier);
            throw new CustomUserMessageAuthenticationException(
                "Trop de tentatives. Réessayez dans {$remaining} secondes."
            );
        }

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();

        // Si l'utilisateur a activé la 2FA, on stocke juste l'ID en session
        if (method_exists($user, 'getTotpSecret') && $user->getTotpSecret()) {
            $request->getSession()->set('2fa_user_id', $user->getId());
            // NE PAS rediriger ici, laisser le subscriber gérer la redirection
            return null; // Laisse la redirection par défaut, le subscriber prendra le relais
        }

        // Sinon, rediriger vers la page d'accueil
        return new Response('', 302, ['Location' => $this->router->generate('app_accueil')]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $email = $request->request->get('email', '');
        $identifier = $email . $request->getClientIp();
        $this->loginAttemptService->addAttempt($identifier);

        // Redirige vers la page de login avec l'erreur
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        return new Response('', 302, ['Location' => $this->router->generate('app_login')]);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }
}
