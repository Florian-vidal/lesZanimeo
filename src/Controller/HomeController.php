<?php

namespace App\Controller;

use App\Repository\FamillesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_lesZanimeo")
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