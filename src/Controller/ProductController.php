<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductSubStepsType;
use App\Form\ProductType;
use App\Repository\InspectionRepository;
use App\Repository\ProductRepository;
use App\Repository\SubStepRepository;
use Cassandra\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                $violations = $validator->validate(
                    $file,
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/*',
                        ]
                    ])
                );
                if (count($violations)) {
                    $this->addFlash('error', 'File Invalid');
                    return $this->redirectToRoute('product_new');
                }
                $filename = uniqid('file-', false) . '.' . $file->guessExtension();
                $file->move($this->getParameter('upload_dir'), $filename);
                $product->setImage($filename);
            }
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                $violations = $validator->validate(
                    $file,
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/*',
                        ]
                    ])
                );
                if (count($violations)) {
                    $this->addFlash('error', 'File Invalid');
                    return $this->redirectToRoute('product_new');
                }
                $filename = uniqid('file-', false) . '.' . $file->guessExtension();
                $file->move($this->getParameter('upload_dir'), $filename);
                $product->setImage($filename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/r-image/{id}", name="product_remove_image")
     */
    public function productRemoveImage(Product $product): RedirectResponse
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getParameter('upload_dir') . '/' . $product->getImage());
        $product->setImage(null);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
    }

    /**
     * @Route("/aps/{id}", name="product_assign_steps")
     */
    public function assignInspectionSteps(Product $product, Request $request, SubStepRepository $subStepRepository, InspectionRepository $inspectionRepository): Response
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $product->clearSubsteps();
            foreach ($request->request->get('subtests') as $subTestId) {
                $product->addSubstep($subStepRepository->find($subTestId));
            }
            $entityManager->flush();
        }
        return $this->render('product/sub-steps.html.twig', [
            'product' => $product,
            'inspections' => $inspectionRepository->findBy(['deletedAt' => null]),
            'form' => $form->createView()
        ]);
    }
}
