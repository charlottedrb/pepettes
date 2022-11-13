<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Review;
use App\Form\PlaceType;
use App\Form\ReviewType;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/place')]
class PlaceController extends AbstractController
{
    #[Route('/', name: 'app_place_index', methods: ['GET'])]
    public function index(PlaceRepository $placeRepository, CityRepository $cityRepository): Response
    {
        return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findAll(),
            'cities' => $cityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_place_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlaceRepository $placeRepository): Response
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $placeRepository->save($place, true);

            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('place/new.html.twig', [
            'place' => $place,
            'form' => $form,
        ]);
    }

    #[Route('/city/{city}', name: 'app_place_filter_by_city', methods: ['GET'])]
    public function filteredByCity(PlaceRepository $placeRepository, CityRepository $cityRepository, City $city): Response
    {
        return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findByCity($city),
            'cities' => $cityRepository->findAll(),
            'selectedCity' => $city
        ]);
    }

    #[Route('/la_creme_de_la_creme', name: 'app_place_best', methods: ['GET'])]
    public function best(PlaceRepository $placeRepository, CityRepository $cityRepository): Response
    {
        return $this->render('pages/la-creme-de-la-creme.html.twig', [
            'places' => $placeRepository->findBestPlaces(),
            'cities' => $cityRepository->findAll(),
        ]);
    }

    #[Route('/la_creme_de_la_creme/city/{city}', name: 'app_best_place_filter_by_city', methods: ['GET'])]
    public function bestFilteredByCity(PlaceRepository $placeRepository, CityRepository $cityRepository, City $city): Response 
    {
        return $this->render('pages/la-creme-de-la-creme.html.twig', [
            'places' => $placeRepository->findBestPlacesByCity($city),
            'cities' => $cityRepository->findAll(),
            'selectedCity' => $city
        ]);
    }

    #[Route('/{id}', name: 'app_place_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Place $place, ReviewRepository $reviewRepository): Response
    {
        $review = new Review();
        $reviewForm = $this->createForm(ReviewType::class, $review);
        $reviewForm->handleRequest($request);

        if($reviewForm->isSubmitted() && $reviewForm->isValid()) {
            $review->setPlace($place);
            $reviewRepository->save($review, true);

            return $this->redirectToRoute('app_place_show', ['id' => $place->getId()]);
        }
        
        return $this->renderForm('place/show.html.twig', [
            'place' => $place,
            'form' => $reviewForm,
            'reviews' => $reviewRepository->findByPlace($place)
        ]);
    }

    #[Route('/{id}/edit', name: 'app_place_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Place $place, PlaceRepository $placeRepository): Response
    {
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $placeRepository->save($place, true);

            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('place/edit.html.twig', [
            'place' => $place,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_place_delete', methods: ['POST'])]
    public function delete(Request $request, Place $place, PlaceRepository $placeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $placeRepository->remove($place, true);
        }

        return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
    }
}
