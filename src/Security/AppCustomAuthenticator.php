<?php

namespace App\Security;

use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppCustomAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private PersonnelRepository $personnelRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        PersonnelRepository $personnelRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->personnelRepository = $personnelRepository;
        $this->entityManager = $entityManager;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->getPayload()->getString('email');
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        $user = $this->personnelRepository->findOneBy(['email' => $email]);

        if ($user) {
            $now = new \DateTime();
            if ($user->getBlockedUntil() !== null && $user->getBlockedUntil() > $now) {
                $remaining = $user->getBlockedUntil()->getTimestamp() - $now->getTimestamp();
                throw new CustomUserMessageAuthenticationException(
                    'Compte bloqué temporairement. Réessayez dans ' . $remaining . ' secondes.'
                );
            }
        }

        return new Passport(
            new UserBadge($email, function ($userIdentifier) use ($request) {
                $user = $this->personnelRepository->findOneBy(['email' => $userIdentifier]);

                if (!$user) {
                    throw new CustomUserMessageAuthenticationException('Email ou mot de passe incorrect.');
                }

                return $user;
            }),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Réinitialiser les compteurs en cas de succès
        $user = $token->getUser();
        if (method_exists($user, 'setLoginAttempts')) {
            $user->setLoginAttempts(0);
            $user->setBlockedUntil(null);
            $this->entityManager->flush();
        }

        // Redirige TOUJOURS vers l'accueil après connexion
        return new RedirectResponse($this->urlGenerator->generate('app_accueil'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $email = $request->getPayload()->getString('email');
        $user = $this->personnelRepository->findOneBy(['email' => $email]);

        if ($user) {
            $now = new \DateTime();
            $user->setLoginAttempts($user->getLoginAttempts() + 1);

            // Blocage progressif : 3 tentatives = 30s, 4 = 1min, 5 = 2min, 6+ = 5min
            if ($user->getLoginAttempts() >= 3) {
                $durations = [30, 60, 120, 300]; // en secondes
                $index = min($user->getLoginAttempts() - 3, count($durations) - 1);
                $blockDuration = $durations[$index];
                $user->setBlockedUntil((clone $now)->modify("+$blockDuration seconds"));
            }

            $this->entityManager->flush();
        }

        return parent::onAuthenticationFailure($request, $exception);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
