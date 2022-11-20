<?php

namespace App\Controller;

use App\Entity\Place;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/review')]
class ReviewController extends AbstractController
{
    #[Route('/', name: 'app_review_index', methods: ['GET'])]
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('review/index.html.twig', [
            'reviews' => $reviewRepository->findAll(),
        ]);
    }

    #[Route('/new/{place}', name: 'app_review_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReviewRepository $reviewRepository, Place $place): Response
    {
        $reviews = $reviewRepository->findByPlace($place);
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($this->getUser()) {
                $review->setUser($this->getUser());
                $review->setPlace($place);
                $reviewRepository->save($review, true);
    
                $this->addFlash('success', 'L\'avis a bien été envoyé.');
                return $this->redirectToRoute('app_place_show', ['id' => $place->getId()]);
            } else {
                $this->addFlash('error', 'Tu dois être connecté(e) pour laisser un avis.');
                return $this->redirectToRoute('app_place_show', ['id' => $place->getId()]);
            }
        }

        return $this->renderForm('place/show.html.twig', [
            'place' => $place,
            'reviews' => $reviews,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_review_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Review $review, ReviewRepository $reviewRepository): Response
    {
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        $this->denyAccessUnlessGranted('REVIEW_EDIT', $review);
        if ($form->isSubmitted() && $form->isValid()) {
            $reviewRepository->save($review, true);

            $this->addFlash('success', 'L\'avis a bien été modifié.');
            return $this->redirectToRoute('app_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('review/edit.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_review_delete', methods: ['POST'])]
    public function delete(Request $request, Review $review, ReviewRepository $reviewRepository): Response
    {
        $this->denyAccessUnlessGranted('REVIEW_DELETE', $review);
        if ($this->isCsrfTokenValid('delete' . $review->getId(), $request->request->get('_token'))) {
            $reviewRepository->remove($review, true);
        }
        $this->addFlash('success', 'L\'avis a bien été supprimé.');

        return new RedirectResponse($request->headers->get('referer'));
    }
}
