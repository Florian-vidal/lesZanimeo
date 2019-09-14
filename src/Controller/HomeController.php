<?php

namespace App\Controller;

use App\Repository\FamillesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends AbstractController
{


    /**
     * @Route("/", name="intro")
     */
    public function introController()
    {
        return $this->render('intro.html.twig');
    }

    /**
     * @Route("home", name="home_lesZanimeo")
     */
    public function indexController(FamillesRepository $famillesRepository)
    {
        $familles = $famillesRepository->findAll();
        return $this->render('home.html.twig',
            [
                'familles'=>$familles
            ]
        );
    }






}