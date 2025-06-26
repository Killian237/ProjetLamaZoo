<?php

namespace App\Controller;

use App\Repository\AteliersRepository;
use App\Repository\PanierRepository;
use App\Entity\Contenir;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AteliersController extends AbstractController
{
    #[Route('/ateliers', name: 'app_ateliers')]
    public function index(AteliersRepository $ateliersRepository): Response
    {
        $ateliers = $ateliersRepository->findAll();
        return $this->render('ateliers/index.html.twig', [
            'ateliers' => $ateliers,
        ]);
    }

    #[Route('/ateliers/add/{atelier}', name: 'app_ateliers_add', methods: ['POST'])]
    public function addToCart(
        int                    $atelier,
        Request                $request,
        AteliersRepository     $ateliersRepository,
        PanierRepository       $panierRepository,
        EntityManagerInterface $em
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $atelierEntity = $ateliersRepository->find($atelier);


        $panier = $panierRepository->findOneBy(['personnel' => $user, 'regler' => false]);
        if (!$panier) {
            $panier = new Panier();
            $panier->setPersonnel($user);
            $panier->setDateCreation(new \DateTime());
            $panier->setRegler(false);
            $em->persist($panier);
        }

        $heureStr = $request->request->get('heure');
        $heure = \DateTime::createFromFormat('H:i', $heureStr);

        $contenir = new Contenir();
        $contenir->setPanier($panier);
        $contenir->setAteliers($atelierEntity);
        $contenir->setHeureChoisit($heure);


        $em->persist($contenir);

        $em->flush();

        return $this->redirectToRoute('app_parrainage_panier');
    }

    #[Route('/ateliers/panier/retirer/{atelier}', name: 'app_ateliers_panier_retirer')]
    public function retirerDuPanier(
        int                    $atelier,
        PanierRepository       $panierRepository,
        EntityManagerInterface $em
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $panier = $panierRepository->findOneBy(['personnel' => $user, 'regler' => false]);
        if ($panier) {
            foreach ($panier->getContenirs() as $contenir) {
                if ($contenir->getAteliers() && $contenir->getAteliers()->getId() === $atelier) {
                    $em->remove($contenir);
                }
            }
            $em->flush();
        }

        return $this->redirectToRoute('app_parrainage_panier');
    }

    #[Route('/ateliers/panier/modifier-heure/{atelier}', name: 'app_ateliers_panier_modifier_heure', methods: ['POST'])]
    public function modifierHeure(
        int                    $atelier,
        Request                $request,
        PanierRepository       $panierRepository,
        EntityManagerInterface $em
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $panier = $panierRepository->findOneBy(['personnel' => $user, 'regler' => false]);
        if ($panier) {
            foreach ($panier->getContenirs() as $contenir) {
                if ($contenir->getAteliers() && $contenir->getAteliers()->getId() === $atelier) {
                    $heureStr = $request->request->get('heure');
                    $heure = \DateTime::createFromFormat('H:i', $heureStr);
                    $contenir->setHeureChoisit($heure);
                }
            }
            $em->flush();
        }
        return $this->redirectToRoute('app_parrainage_panier');
    }
}