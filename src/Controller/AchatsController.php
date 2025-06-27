<?php

namespace App\Controller;

use App\Repository\ParticiperRepository;
use App\Repository\AnimauxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AchatsController extends AbstractController
{
    #[Route('/mes-achats', name: 'app_mes_achats')]
    public function index(
        ParticiperRepository $participerRepository,
        AnimauxRepository    $animauxRepository
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $participations = $participerRepository->findBy(['personnel' => $user]);

        $animauxParraines = $animauxRepository->findBy(['parrainage' => $user->getParrainage()]);

        return $this->render('achats/index.html.twig', [
            'participations' => $participations,
            'animauxParraines' => $animauxParraines,
        ]);
    }
}