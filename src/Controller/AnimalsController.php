<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AnimalsController extends AbstractController
{
    /**
     * @Route("farm", name="farm_page")
     */
    public function farmController()
    {
        return $this->render('farm.html.twig');
    }
}