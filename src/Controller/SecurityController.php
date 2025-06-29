<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use OTPHP\TOTP;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        Request $request,
        PersonnelRepository $personnelRepository
    ): Response
    {
        $remainingTime = null;
        $email = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

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
            'error' => $error,
            'remaining_time' => $remainingTime,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // --- Activation 2FA ---
    #[Route('/2fa/enable', name: 'app_2fa_enable', methods: ['GET', 'POST'])]
    public function enable2fa(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getTotpSecret()) {
            $this->addFlash('info', '2FA déjà activée.');
            return $this->redirectToRoute('app_accueil');
        }

        // Partie POST : vérification du code et enregistrement du secret
        if ($request->isMethod('POST')) {
            $code = $request->request->get('code');
            $secret = $request->getSession()->get('2fa_secret');

            if (!$secret) {
                $this->addFlash('error', 'Erreur de session.');
                return $this->redirectToRoute('app_2fa_enable');
            }

            $totp = TOTP::create($secret);
            if ($totp->verify($code)) {
                $user->setTotpSecret($secret);
                $em->flush();
                $request->getSession()->remove('2fa_secret');
                $this->addFlash('success', '2FA activée !');
                return $this->redirectToRoute('app_accueil');
            } else {
                $this->addFlash('error', 'Code invalide.');
            }
        }

        // Partie GET : génération du secret et du QR code
        $totp = TOTP::generate();
        $secret = $totp->getSecret();
        $request->getSession()->set('2fa_secret', $secret);

        $totp->setLabel($user->getEmail());
        $totp->setIssuer('ProjetLamaZoo');
        $qrCodeUrl = $totp->getProvisioningUri();

        // On passe juste l'URL à la vue, le QR code sera généré en JS côté client
        return $this->render('security/enable_2fa.html.twig', [
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }
}
