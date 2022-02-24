<?php

namespace App\Controller;

use App\Entity\Inspection;
use App\Entity\SubStep;
use App\Form\SubStepType;
use App\Repository\SubStepRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{identifier}/sub-steps")
 */
class SubStepController extends AbstractController
{
    /**
     * @Route("/", name="sub_step_index", methods={"GET"})
     */
    public function index(Inspection $inspection ,SubStepRepository $subStepRepository): Response
    {
        return $this->render('sub_step/index.html.twig', [
            'sub_steps' => $subStepRepository->findBy(['inspection' => $inspection]),
            'inspection' => $inspection
        ]);
    }

    /**
     * @Route("/new", name="sub_step_new", methods={"GET", "POST"})
     */
    public function new(Inspection $inspection ,Request $request, EntityManagerInterface $entityManager): Response
    {
        $subStep = new SubStep();
        $form = $this->createForm(SubStepType::class, $subStep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subStep->setInspection($inspection);
            $entityManager->persist($subStep);
            $entityManager->flush();

            return $this->redirectToRoute('sub_step_index', ['identifier' => $inspection->getIdentifier()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sub_step/new.html.twig', [
            'sub_step' => $subStep,
            'form' => $form->createView(),
            'inspection' => $inspection
        ]);
    }

    /**
     * @Route("/{id}", name="sub_step_show", methods={"GET"})
     */
    public function show(Inspection $inspection ,SubStep $subStep): Response
    {
        return $this->render('sub_step/show.html.twig', [
            'sub_step' => $subStep,
            'inspection' => $inspection
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sub_step_edit", methods={"GET", "POST"})
     */
    public function edit(Inspection $inspection ,Request $request, SubStep $subStep, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubStepType::class, $subStep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('sub_step_index', ['identifier' => $inspection->getIdentifier()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sub_step/edit.html.twig', [
            'sub_step' => $subStep,
            'form' => $form->createView(),
            'inspection' => $inspection
        ]);
    }

    /**
     * @Route("/{id}", name="sub_step_delete", methods={"POST"})
     */
    public function delete(Inspection $inspection ,Request $request, SubStep $subStep, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subStep->getId(), $request->request->get('_token'))) {
            $entityManager->remove($subStep);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sub_step_index', ['identifier' => $inspection->getIdentifier()], Response::HTTP_SEE_OTHER);
    }
}
