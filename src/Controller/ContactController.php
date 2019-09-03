<?php


namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contactForm(Request $request, EntityManagerInterface $entityManager)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $formContactView = $form->createView();

        if ($request->isMethod('Post')) {

            $form->handleRequest($request);

            $entityManager->persist($contact);
            $entityManager->flush();

            if ($form->isSubmitted() && $form->isValid()) {

                $this->addFlash('Success', 'Inscription réussie !');

            } else {

                $this->addFlash('Fail', 'Echec de l\'inscription, veuillez réessayer.');
            }
        }

        return $this->render('contact.html.twig',
            [
                'formContactView' => $formContactView
            ]
        );
    }










}