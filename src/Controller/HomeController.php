<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PlaceRepository $placeRepository): Response
    {
        return $this->render('home.html.twig', [
            'controller_name' => 'HomeController',
            'places' => $placeRepository->findMostRecent(),
        ]);
    }
}
