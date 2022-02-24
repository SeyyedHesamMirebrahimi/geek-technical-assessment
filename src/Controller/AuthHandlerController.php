<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthHandlerController extends AbstractController
{
    /**
     * @Route("/auth-handler", name="auth_handler")
     */
    public function index(): Response
    {
        return  $this->redirectToRoute('inspection_index');
    }
}
