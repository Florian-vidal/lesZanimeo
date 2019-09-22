<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    // ROUTE PAGE CONTACT ----------------------------------------------------------------------------------------------
    /**
     * @Route("contact", name="contact")
     */
    public function contact(EntityManagerInterface $entityManager, Request $request, \Swift_Mailer $mailer)
    {
        // Je créé une instance de l'entité Contact et je créé une représentation abstraite (form = formulaire) avec les
        // champs voulus
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        //Si la méthode est POST, si le formulaire est envoyé
        if ($request->isMethod('POST')) {

            //Le formulaire récupère les infos de la requête
            $form->handleRequest($request);

            // Je reçois un mail depuis mon nouveau user enregistré dans ma table contact
            if ($form->isSubmitted() && $form->isValid()) {
                $message = (new \Swift_Message('Nouveau message'))
                    ->setFrom($contact->getEmail())
                    ->setTo('ezekielsxm@gmail.com')
                    ->setBody(
                        $this->renderView(
                            '_mail.html.twig', [
                                'prenom' => $contact->getPrenom(),
                                'email' => $contact->getEmail(),
                                'message' => $contact->getMessage()
                            ]
                        ),
                        'text/html'
                    );

                $mailer->send($message);

                // L'objet instancié est supprimé et annule les modifications apportées aux objets mis en file d'attente
                // dans la base de données. Synchronise la base de données.
                $entityManager->persist($contact);
                $entityManager->flush();

                $this->addFlash('success', 'Ton message a bien été envoyé, merci ! Nous y répondrons dès que possible.');

                return $this->redirect($request->getUri());

            } else {

                $this->addFlash('fail', 'Votre message n\'a pas pu être envoyé.');

                return $this->render('contact.html.twig',
                    [
                        'contactForm' => $form->createView()
                    ]
                );
            }
        }

        return $this->render('contact.html.twig',
            [
                'contactForm' => $form->createView()
            ]
        );
    }

}