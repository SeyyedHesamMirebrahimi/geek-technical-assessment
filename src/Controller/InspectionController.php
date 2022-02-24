<?php

namespace App\Controller;

use App\Entity\Inspection;
use App\Form\InspectionType;
use App\Repository\InspectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inspection")
 */
class InspectionController extends AbstractController
{
    /**
     * @Route("/", name="inspection_index", methods={"GET"})
     */
    public function index(InspectionRepository $inspectionRepository): Response
    {
        return $this->render('inspection/index.html.twig', [
            'inspections' => $inspectionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="inspection_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $inspection = new Inspection();
        $form = $this->createForm(InspectionType::class, $inspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($inspection);
            $entityManager->flush();

            return $this->redirectToRoute('inspection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inspection/new.html.twig', [
            'inspection' => $inspection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="inspection_show", methods={"GET"})
     */
    public function show(Inspection $inspection): Response
    {
        return $this->render('inspection/show.html.twig', [
            'inspection' => $inspection,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="inspection_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Inspection $inspection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InspectionType::class, $inspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('inspection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inspection/edit.html.twig', [
            'inspection' => $inspection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="inspection_delete", methods={"POST"})
     */
    public function delete(Request $request, Inspection $inspection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inspection->getId(), $request->request->get('_token'))) {
            $entityManager->remove($inspection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('inspection_index', [], Response::HTTP_SEE_OTHER);
    }
}
