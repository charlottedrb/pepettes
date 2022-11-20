<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\Review;
use App\Form\PlaceType;
use App\Form\ReviewType;
use App\Repository\TagRepository;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
    public function new(Request $request, PlaceRepository $placeRepository, SluggerInterface $slugger): Response
    {
        if(!$this->getUser()) {
            $this->addFlash('error', 'Tu dois être connecté(e) pour ajouter une adresse.');
            return new RedirectResponse($request->headers->get('referer'));
        }
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFilename')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('places_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $place->setImageFilename($newFilename);
            }

            $place->setCreatedAt(new \DateTimeImmutable());
            $placeRepository->save($place, true);

            $this->addFlash('success', 'L\'adresse a bien été créée.');

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

    #[Route('/tag/{tag}', name: 'app_place_filter_by_tag', methods: ['GET'])]
    public function filteredByTag(PlaceRepository $placeRepository, TagRepository $tagRepository, Tag $tag): Response
    {
        return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findByTag($tag),
            'tags' => $tagRepository->findAll(),
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

    #[Route('/la_creme_de_la_creme/tag/{tag}', name: 'app_best_place_filter_by_tag', methods: ['GET'])]
    public function bestFilteredByTag(PlaceRepository $placeRepository, TagRepository $tagRepository, Tag $tag): Response
    {
        return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findByTag($tag),
            'tags' => $tagRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_place_show', methods: ['GET'])]
    public function show(Request $request, Place $place, ReviewRepository $reviewRepository): Response
    {
        $review = new Review();
        $reviewForm = $this->createForm(ReviewType::class, $review);
        
        return $this->renderForm('place/show.html.twig', [
            'place' => $place,
            'form' => $reviewForm,
            'reviews' => $reviewRepository->findByPlace($place)
        ]);
    }

    #[Route('/{id}/edit', name: 'app_place_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Place $place, PlaceRepository $placeRepository, SluggerInterface $slugger): Response
    {
        $imageFilename = $place->getImageFilename();
        $place->setImageFilename(
            new File($this->getParameter('places_images_directory').'/'.$place->getImageFilename())
        );

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFilename')->getData();

            if ($imageFile !== null) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('places_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $place->setImageFilename($newFilename);
            } else {
                $place->setImageFilename($imageFilename);
            }

            $placeRepository->save($place, true);
            $this->addFlash('success', 'L\'adresse a bien été modifiée');

            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('place/edit.html.twig', [
            'place' => $place,
            'form' => $form,
            'imageFilename' => $imageFilename
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
