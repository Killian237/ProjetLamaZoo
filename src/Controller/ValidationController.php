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

        $montants = $request->request->get('montant', []);
        $totalParrainage = 0;

        foreach ($panier->getMettres() as $mettre) {
            $animal = $mettre->getAnimaux();
            if ($animal && isset($montants[$animal->getId()])) {
                $montant = (float)$montants[$animal->getId()];
                // Supposons que l'entité Animaux a une méthode addParrainageMontant
                $animal->setParrainageMontant($animal->getParrainageMontant() + $montant);
                $totalParrainage += $montant;
            }
        }

        // Supposons que l'entité User a une méthode setParrainageTotal
        $user->setParrainageTotal($user->getParrainageTotal() + $totalParrainage);

        $panier->setRegler(true);
        $em->flush();

        $this->addFlash('success', 'Votre parrainage a bien été validé !');
        return $this->redirectToRoute('app_parrainage');
    }
}