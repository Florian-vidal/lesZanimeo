<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // ROUTE PAGE INTRO ----------------------------------------------------------------------------------------------
    /**
     * @Route("/", name="intro")
     */
    public function introController()
    {
        return $this->render('intro.html.twig');
    }

    // ROUTE PAGE D'ACCUEIL ----------------------------------------------------------------------------------------------
    /**
     * @Route("home", name="home_lesZanimeo")
     */
    public function indexController()
    {
        return $this->render('home.html.twig');
    }

}