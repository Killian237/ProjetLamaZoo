<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AnimauxRepository;

final class BaseController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(AnimauxRepository $animauxRepository): Response
    {
        $animaux = $animauxRepository->findAllAnimaux();
        return $this->render('base/index.html.twig', [
            'animaux' => $animaux,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('base/contact.html.twig', [
        ]);
    }
}
