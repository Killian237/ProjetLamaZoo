<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use OTPHP\TOTP;

class TwoFactorController extends AbstractController
{
    #[Route('/enable-2fa', name: 'app_enable_2fa', methods: ['GET', 'POST'])]
    public function enable2fa(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }

        // ----- ÉTAPE POST : Vérification du code et enregistrement du secret -----
        if ($request->isMethod('POST')) {
            $code = $request->request->get('code');
            $secret = $request->getSession()->get('2fa_secret');

            if (!$secret) {
                $this->addFlash('error', 'Erreur de session.');
                return $this->redirectToRoute('app_enable_2fa');
            }

            $totp = TOTP::create($secret);
            if ($totp->verify($code)) {
                // Enregistrer le secret dans la base via le setter
                $user->setTotpSecret($secret);
                $em->persist($user);
                $em->flush();

                $request->getSession()->remove('2fa_secret');
                $this->addFlash('success', 'Double authentification activée !');
                // Redirige vers la page d'acceuil'
                return $this->redirectToRoute('app_accueil');
            } else {
                $this->addFlash('error', 'Code invalide.');
            }
        }

        // ----- ÉTAPE GET : Génération du secret et du QR code -----
        $secret = $request->getSession()->get('2fa_secret');
        if (!$secret) {
            $totp = TOTP::generate();
            $secret = $totp->getSecret();
            $request->getSession()->set('2fa_secret', $secret);
        } else {
            $totp = TOTP::create($secret);
        }

        $totp->setLabel($user->getEmail());
        $totp->setIssuer('ProjetLamaZoo');
        $qrCodeUrl = $totp->getProvisioningUri();

        return $this->render('security/enable_2fa.html.twig', [
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    
}
