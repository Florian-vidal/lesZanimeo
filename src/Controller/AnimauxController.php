<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AnimauxController extends AbstractController
{
    /**
     * @Route("farm", name="farm_page")
     */
    public function farmController()
    {
        return $this->render('farm.html.twig');
    }

    /**
     * @Route("bugs", name="bugs_page")
     */
    public function bugsController()
    {
        return $this->render('bugs.html.twig');
    }

    /**
     * @Route("birds", name="birds_page")
     */
    public function birdsController()
    {
        return $this->render('birds.html.twig');
    }

    /**
     * @Route("dog", name="dog_page")
     */
    public function dogController()
    {
        return $this->render('animals/dog.html.twig');
    }






}