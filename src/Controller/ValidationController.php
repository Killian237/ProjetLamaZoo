<?php

namespace App\Controller;

use App\Repository\PanierRepository;
use App\Repository\AnimauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ValidationController extends AbstractController
{
    #[Route('/parrainage/valider', name: 'app_parrainage_valider', methods: ['POST'])]
    public function valider(
        Request                $request,
        PanierRepository       $panierRepository,
        AnimauxRepository      $animauxRepository,
        EntityManagerInterface $em
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $panier = $panierRepository->findOneBy(['personnel' => $user, 'regler' => false]);
        if (!$panier) {
            $this->addFlash('danger', 'Aucun panier à valider.');
            return $this->redirectToRoute('app_parrainage_panier');
        }

        $montants = $request->request->all('montant');
        $totalParrainage = 0;

        foreach ($panier->getMettres() as $mettre) {
            $animal = $mettre->getAnimaux();
            if ($animal && isset($montants[$animal->getId()])) {
                $montant = (float)$montants[$animal->getId()];
                $animal->setParrainage($animal->getParrainage() + $montant);
                $totalParrainage += $montant;
            }
        }

        foreach ($panier->getContenirs() as $contenir) {
            $atelier = $contenir->getAteliers();
            if ($atelier) {
                $participation = new \App\Entity\Participer();
                $participation->setPersonnel($user);
                $participation->setAtelier($atelier);
                $heureChoisie = $contenir->getHeureChoisit();
                $participation->setHeureDebut($heureChoisie);
                $heureChoisie = $contenir->getHeureChoisit();
                $dure = $atelier->getDure();

                if ($dure instanceof \DateTime) {
                    $minutes = ((int)$dure->format('H')) * 60 + (int)$dure->format('i');
                } else {
                    $minutes = (int)$dure;
                }

                $heureFin = (clone $heureChoisie)->modify('+' . $minutes . ' minutes');
                $participation->setHeureFin($heureFin);
                $em->persist($participation);
            }
        }

        $user->setParrainage($user->getParrainage() + $totalParrainage);

        $panier->setRegler(true);
        $em->flush();

        $this->addFlash('success', 'Votre parrainage a bien été validé !');
        return $this->redirectToRoute('app_parrainage');
    }
}