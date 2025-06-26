<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AnimauxRepository;
use App\Entity\Panier;
use App\Entity\Mettre;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;

class ParrainageController extends AbstractController
{
    #[Route('/parrainage', name: 'app_parrainage')]
    public function index(AnimauxRepository $animauxRepository): Response
    {
        $animaux = $animauxRepository->findAllAnimaux();
        return $this->render('parrainage/index.html.twig', [
            'animaux' => $animaux,
        ]);
    }

    #[Route('/parrainage/add/{animal}', name: 'app_parrainage_add')]
    public function addToCart(
        int                    $animal,
        AnimauxRepository      $animauxRepository,
        PanierRepository       $panierRepository,
        EntityManagerInterface $em
    ): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $animalEntity = $animauxRepository->find($animal);

        $panier = $panierRepository->findOneBy(['personnel' => $user, 'regler' => false]);
        if (!$panier) {
            $panier = new Panier();
            $panier->setPersonnel($user);
            $panier->setDateCreation(new \DateTime());
            $panier->setRegler(false);
            $em->persist($panier);
        }

        $dejaPresent = false;
        foreach ($panier->getMettres() as $mettre) {
            if ($mettre->getAnimaux() && $mettre->getAnimaux()->getId() === $animalEntity->getId()) {
                $dejaPresent = true;
                break;
            }
        }
        if (!$dejaPresent) {
            $mettre = new Mettre();
            $mettre->setPanier($panier);
            $mettre->setAnimaux($animalEntity);
            $em->persist($mettre);
        }

        $em->flush();

        return $this->redirectToRoute('app_parrainage');
    }

    #[Route('/parrainage/panier', name: 'app_parrainage_panier')]
    public function panier(PanierRepository $panierRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $panier = $panierRepository->findOneBy(['personnel' => $user, 'regler' => false]);
        $cart = [];
        $animals = [];
        if ($panier) {
            foreach ($panier->getMettres() as $mettre) {
                $animal = $mettre->getAnimaux();
                if ($animal) {
                    $cart[] = $animal->getId();
                    $animals[$animal->getId()] = $animal;
                }
            }
        }

        $contenirAteliers = [];
        if ($panier) {
            foreach ($panier->getContenirs() as $contenir) {
                if ($contenir->getAteliers()) {
                    $contenirAteliers[] = $contenir;
                }
            }
        }

        return $this->render('parrainage/panier.html.twig', [
            'cart' => $cart,
            'animals' => $animals,
            'contenirAteliers' => $contenirAteliers,
        ]);

    }

    #[Route('/parrainage/panier/retirer/{animal}', name: 'app_parrainage_panier_retirer')]
    public function retirerDuPanier(
        int                    $animal,
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
            foreach ($panier->getMettres() as $mettre) {
                if ($mettre->getAnimaux() && $mettre->getAnimaux()->getId() === $animal) {
                    $em->remove($mettre);
                }
            }
            $em->flush();
        }

        return $this->redirectToRoute('app_parrainage_panier');
    }
}