<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\LoginAttemptService;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PersonnelRepository;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, PersonnelRepository $personnelRepository): Response
{
    $remainingTime = null;

    // Récupère le dernier email tenté
    $email = $request->getSession()->get('_security.last_username');
    if ($email) {
        $user = $personnelRepository->findOneBy(['email' => $email]);
        if ($user && $user->getBlockedUntil() !== null) {
            $now = new \DateTime();
            if ($user->getBlockedUntil() > $now) {
                $remainingTime = $user->getBlockedUntil()->getTimestamp() - $now->getTimestamp();
            }
        }
    }

    return $this->render('security/login.html.twig', [
        'last_username' => $email,
        'error' => null, // ou ton système d'erreur habituel
        'remaining_time' => $remainingTime,
    ]);
}
    
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    
}
