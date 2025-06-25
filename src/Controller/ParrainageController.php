<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class ParrainageController extends AbstractController
{
    #[Route('/parrainage', name: 'app_parrainage')]
    public function index(): Response
    {
        return $this->render('parrainage/index.html.twig');
    }

    #[Route('/parrainage/add/{animal}', name: 'app_parrainage_add')]
    public function addToCart(string $animal, Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('parrainage_cart', []);
        if (!in_array($animal, $cart)) {
            $cart[] = $animal;
        }
        $session->set('parrainage_cart', $cart);

        return $this->redirectToRoute('app_parrainage');
    }

    #[Route('/parrainage/panier', name: 'app_parrainage_panier')]
    public function panier(Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('parrainage_cart', []);
        $animals = [
            'lama' => ['nom' => 'Lama', 'emoji' => '🦙'],
            'zebre' => ['nom' => 'Zèbre', 'emoji' => '🦓'],
            'lion' => ['nom' => 'Lion', 'emoji' => '🦁'],
            'girafe' => ['nom' => 'Girafe', 'emoji' => '🦒'],
            'elephant' => ['nom' => 'Éléphant', 'emoji' => '🐘'],
        ];

        return $this->render('parrainage/panier.html.twig', [
            'cart' => $cart,
            'animals' => $animals,
        ]);
    }

    #[Route('/parrainage/panier/retirer/{animal}', name: 'app_parrainage_panier_retirer')]
    public function retirerDuPanier(string $animal, Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('parrainage_cart', []);
        if (($key = array_search($animal, $cart)) !== false) {
            unset($cart[$key]);
            $cart = array_values($cart); // Réindexe le tableau
        }
        $session->set('parrainage_cart', $cart);

        return $this->redirectToRoute('app_parrainage_panier');
    }
}