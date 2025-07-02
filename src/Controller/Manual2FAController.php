<?php

namespace App\Controller;

use OTPHP\TOTP;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Manual2FAController extends AbstractController
{
    #[Route('/manual-2fa-check', name: 'app_manual_2fa_check', methods: ['GET', 'POST'])]
    public function check2fa(Request $request): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();
        
        // Redirige vers la page de login si non connecté
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $error = null;

        // Traitement de la soumission du formulaire
        if ($request->isMethod('POST')) {
            $code = $request->request->get('code');
            
            //récupération du secret depuis votre entité
            $secret = $user->getTotpSecret(); 
            
            // Vérification du code
            $totp = TOTP::create($secret);
            
            if ($totp->verify($code)) {
                $this->addFlash('success', 'Code 2FA valide !');
                return $this->redirectToRoute('app_accueil');
            } else {
                $error = 'Code invalide.';
            }
        }

        // Affichage du formulaire
        return $this->render('security/manual_2fa_check.html.twig', [
            'error' => $error
        ]);
    }
}
