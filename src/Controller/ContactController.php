<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Tests\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

class ContactController extends AbstractController
{
    /*-----Public et envoie en BDD pour client: formulaire de contact---------*/
    /*-----ajouté une confirmation d'envoie-----------------*/
    /**
     * @Route("contact", name="contact")
     */
    public function contact(EntityManagerInterface $entityManager, Request $request, \Swift_Mailer $mailer)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

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

                $entityManager->persist($contact);
                $entityManager->flush();

                $this->addFlash('success', 'Ton message a bien été envoyé, merci ! Nous y répondrons dès que possible.');

                return $this->redirect($request->getUri());

            } else {

                $this->addFlash('fail', 'Votre message n\'a pas pu être envoyé.');

                return $this->render('contact.html.twig', [
                    'contactForm' => $form->createView()
                ]);
            }
        }

        return $this->render('contact.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }


}