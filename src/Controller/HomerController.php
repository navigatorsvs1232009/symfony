<?php

namespace App\Controller;

use App\Entity\Homer;
use App\Form\HomerType;
use App\Repository\HomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/homer')]
class HomerController extends AbstractController
{
    #[Route('/', name: 'app_homer_index', methods: ['GET'])]
    public function index(HomerRepository $homerRepository): Response
    {
        return $this->render('homer/index.html.twig', [
            'homers' => $homerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_homer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $homer = new Homer();
        $form = $this->createForm(HomerType::class, $homer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($homer);
            $entityManager->flush();

            return $this->redirectToRoute('app_homer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('homer/new.html.twig', [
            'homer' => $homer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_homer_show', methods: ['GET'])]
    public function show(Homer $homer): Response
    {
        return $this->render('homer/show.html.twig', [
            'homer' => $homer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_homer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Homer $homer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HomerType::class, $homer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_homer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('homer/edit.html.twig', [
            'homer' => $homer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_homer_delete', methods: ['POST'])]
    public function delete(Request $request, Homer $homer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$homer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($homer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_homer_index', [], Response::HTTP_SEE_OTHER);
    }
}
