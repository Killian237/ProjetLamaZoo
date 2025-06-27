<?php

namespace App\Security;

use App\Service\LoginAttemptService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    private $loginAttemptService;

    public function __construct(LoginAttemptService $loginAttemptService)
    {
        $this->loginAttemptService = $loginAttemptService;
    }

    public function authenticate(Request $request): Passport
    {
        $identifier = $request->request->get('email') . $request->getClientIp();
        
        if ($this->loginAttemptService->isBlocked($identifier)) {
            $remaining = $this->loginAttemptService->getRemainingTime($identifier);
            throw new CustomUserMessageAuthenticationException(
                "Trop de tentatives. Réessayez dans {$remaining} secondes."
            );
        }

        
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $identifier = $request->request->get('email') . $request->getClientIp();
        $this->loginAttemptService->addAttempt($identifier);
        
        
    }
}
