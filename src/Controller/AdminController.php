<?php

namespace App\Controller;

use App\Entity\Animaux;
use App\Entity\Questions;
use App\Entity\Responses;
use App\Form\AnimalsType;
use App\Form\QuestionsType;
use App\Form\ResponsesType;
use App\Repository\AnimauxRepository;
use App\Repository\QuestionsRepository;
use App\Repository\ResponsesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdminController extends AbstractController
{
    // ROUTE DE L'ESPACE ADMIN ACCUEIL -----------------------------------------------------------------------------------------
    /**
     * @Route("/admin", name="admin")
     */
    public function index(AnimauxRepository $animauxRepository, QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository)
    {
        $animals = $animauxRepository->findAll();
        $questions = $questionsRepository->findAll();
        $responses = $responsesRepository->findAll();

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                return $this->render('admin/admin.html.twig',
                    [
                        'animals'=>$animals,
                        'questions'=>$questions,
                        'responses'=>$responses
                    ]
                );
            } else {
                return $this->redirectToRoute('home_lesZanimeo');
            }
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }


    // ROUTE DE L'INSERT ANIMAUX ESPACE ADMIN ----------------------------------------------------------------------------------
    /**
     * @Route("/admin/form_animals_insert", name="form_animals_insert")
     * Méthode qui permet d'ajouter un animal
     * Je créé tout d'abord mon formulaire d'insertion dans mon fichier Twig "adminAnimalsInsert.html.twig" avec la variable "formAnimalsView".
     * Cette variable est générée grâce à la méthode "createView" présente ci-dessous.
     * La variable stocke le résultat de la méthode "createForm" qui a pour but de générer une représentation abstraite du formulaire (objet)
     * + l'instance de l'entité Animaux. "Handle Request" fait le lien entre les données entrées et le formulaire.
     * J'ai implémenté une méthode pour pouvoir chercher une image depuis son ordinateur grâce à un bouton "choisir un fichier".
     * Aussi, j'ai mis en place un message de confirmation ou d'infirmation en cas de résussite/échec de soumission du formulaire.
     */
    public function insertAnimals(Request $request, EntityManagerInterface $entityManager, AnimauxRepository $animauxRepository)
    {
        $animal = new Animaux();
        $form = $this->createForm(AnimalsType::class, $animal);
        $formAnimalsView = $form->createView();

        if ($request->isMethod('Post')) {

            $form->handleRequest($request);
            /** @var UploadedFile $imageFile */
            $imageFile = $form['image']->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                // Nécessaire pour inclure le nom du fichier en tant qu'URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Déplace le fichier dans le dossier des images de l'image
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                // Met à jour l'image pour stocker le nouveau nom de l'image
                $animal->setImage($newFilename);
            }

            $entityManager->persist($animal);
            $entityManager->flush();

            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash('Success', 'L\'animal a bien été enregistré !');
            } else {
                $this->addFlash('Fail', 'L\'animal n\'a pas été enregistré, veuillez réessayer.');
            }
        }

        return $this->render('admin/adminAnimalsInsert.html.twig',
            [
                'formAnimalsView' => $formAnimalsView
            ]
        );
    }


    // ROUTE DE L'UPDATE ANIMALS ESPACE ADMIN ----------------------------------------------------------------------------------
    /**
     * @Route("/admin/form_animals_update/{id}", name="form_animals_update")
     *
     */
    public function updateAnimals($id, Request $request, EntityManagerInterface $entityManager, AnimauxRepository $animauxRepository)
    {
        $animals = $animauxRepository->find($id);
        $form = $this->createForm(AnimalsType::class, $animals);

        $formAnimalsView = $form->createView();

        if ($request->isMethod('Post')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $entityManager->persist($animals);
                $entityManager->flush();

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->addFlash('Success', 'L\'animal a bien été modifié !');
                } else {
                    $this->addFlash('Fail', 'L\'animal n\'a pas été modifié, veuillez réessayer.');
                }
            }
        }

        return $this->render('admin/adminAnimalsUpdate.html.twig',
            [
                'formAnimalsView' => $formAnimalsView
            ]
        );
    }


    // ROUTE DU DELETE ANIMALS ESPACE ADMIN ------------------------------------------------------------------------------------
    /**
     * @Route("/admin/animals/{id}/delete", name="animals_delete")
     */
    public function removeAnimals($id, AnimauxRepository $animauxRepository, EntityManagerInterface $entityManager)
    {
        $animals = $animauxRepository->find($id);

        $entityManager->remove($animals);
        $entityManager->flush();

        $this->addFlash('Success', 'L\'animal a bien été supprimé !');

        return $this->render('base/message.html.twig');
    }


    // ROUTE DE L'INSERT QUESTIONS ESPACE ADMIN ----------------------------------------------------------------------------------
    /**
     * @Route("/admin/form_questions_insert", name="form_questions_insert")
     */
    public function insertQuestions(Request $request, EntityManagerInterface $entityManager, QuestionsRepository $questionsRepository)
    {
        $question = new Questions();
        $form = $this->createForm(QuestionsType::class, $question);
        $formQuestionsView = $form->createView();

        if ($request->isMethod('Post')) {

            $form->handleRequest($request);
            /** @var UploadedFile $imageFile */
            $imageFile = $form['image']->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                // Nécessaire pour inclure le nom du fichier en tant qu'URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Déplace le fichier dans le dossier des images de l'image
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                // Met à jour l'image pour stocker le nouveau nom de l'image
                $question->setImage($newFilename);
            }

            $entityManager->persist($question);
            $entityManager->flush();

            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash('Success', 'La question a bien été enregistrée !');
            } else {
                $this->addFlash('Fail', 'La question n\'a pas été enregistrée, veuillez réessayer.');
            }
        }

        return $this->render('admin/adminQuestionsInsert.html.twig',
            [
                'formQuestionsView' => $formQuestionsView
            ]
        );
    }


    // ROUTE DE L'UPDATE QUESTIONS ESPACE ADMIN ----------------------------------------------------------------------------------
    /**
     * @Route("/admin/form_questions_update/{id}", name="form_questions_update")
     *
     */
    public function updateQuestions($id, Request $request, EntityManagerInterface $entityManager, QuestionsRepository $questionsRepository)
    {
        $questions = $questionsRepository->find($id);
        $form = $this->createForm(QuestionsType::class, $questions);

        $formQuestionsView = $form->createView();

        if ($request->isMethod('Post')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $entityManager->persist($questions);
                $entityManager->flush();

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->addFlash('Success', 'La question a bien été modifiée !');
                } else {
                    $this->addFlash('Fail', 'La question n\'a pas été modifiée, veuillez réessayer.');
                }
            }
        }

        return $this->render('admin/adminQuestionsUpdate.html.twig',
            [
                'formQuestionsView' => $formQuestionsView
            ]
        );
    }


    // ROUTE DU DELETE QUESTION ESPACE ADMIN ------------------------------------------------------------------------------------
    /**
     * @Route("/admin/questions/{id}/delete", name="questions_delete")
     */
    public function removeQuestions($id, QuestionsRepository $questionsRepository, EntityManagerInterface $entityManager)
    {
        $questions = $questionsRepository->find($id);

        $entityManager->remove($questions);
        $entityManager->flush();

        $this->addFlash('Success', 'La question a bien été supprimée !');

        return $this->render('base/message.html.twig');
    }

    // ROUTE DE L'INSERT REPONSES ESPACE ADMIN ----------------------------------------------------------------------------------
    /**
     * @Route("/admin/form_responses_insert", name="form_responses_insert")
     * Méthode qui permet d'ajouter un animal
     * Je créé tout d'abord mon formulaire d'insertion dans mon fichier Twig "adminAnimalsInsert.html.twig" avec la variable "formAnimalsView".
     * Cette variable est générée grâce à la méthode "createView" présente ci-dessous.
     * La variable stocke le résultat de la méthode "createForm" qui a pour but de générer une représentation abstraite du formulaire (objet)
     * + l'instance de l'entité Animaux. "Handle Request" fait le lien entre les données entrées et le formulaire.
     * J'ai implémenté une méthode pour pouvoir chercher une image depuis son ordinateur grâce à un bouton "choisir un fichier".
     * Aussi, j'ai mis en place un message de confirmation ou d'infirmation en cas de résussite/échec de soumission du formulaire.
     */
    public function insertResponses(Request $request, EntityManagerInterface $entityManager, ResponsesRepository $responsesRepository)
    {
        $response = new Responses();
        $form = $this->createForm(ResponsesType::class, $response);
        $formResponsesView = $form->createView();

        if ($request->isMethod('Post')) {

            $form->handleRequest($request);
            /** @var UploadedFile $imageFile */
            $imageFile = $form['image']->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                // Nécessaire pour inclure le nom du fichier en tant qu'URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Déplace le fichier dans le dossier des images de l'image
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                // Met à jour l'image pour stocker le nouveau nom de l'image
                $response->setImage($newFilename);
            }

            $entityManager->persist($response);
            $entityManager->flush();

            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash('Success', 'La réponse a bien été enregistrée !');
            } else {
                $this->addFlash('Fail', 'La réponse n\'a pas été enregistrée, veuillez réessayer.');
            }
        }

        return $this->render('admin/adminResponsesInsert.html.twig',
            [
                'formResponsesView' => $formResponsesView
            ]
        );
    }


    // ROUTE DE L'UPDATE REPONSES ESPACE ADMIN ----------------------------------------------------------------------------------
    /**
     * @Route("/admin/form_responses_update/{id}", name="form_responses_update")
     *
     */
    public function updateResponses($id, Request $request, EntityManagerInterface $entityManager, ResponsesRepository $responsesRepository)
    {
        $responses = $responsesRepository->find($id);
        $form = $this->createForm(ResponsesType::class, $responses);

        $formResponsesView = $form->createView();

        if ($request->isMethod('Post')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $entityManager->persist($responses);
                $entityManager->flush();

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->addFlash('Success', 'La response a bien été modifiée !');
                } else {
                    $this->addFlash('Fail', 'La response n\'a pas été modifiée, veuillez réessayer.');
                }
            }
        }

        return $this->render('admin/adminResponsesUpdate.html.twig',
            [
                'formResponsesView' => $formResponsesView
            ]
        );
    }


    // ROUTE DU DELETE REPONSE ESPACE ADMIN ------------------------------------------------------------------------------------
    /**
     * @Route("/admin/responses/{id}/delete", name="responses_delete")
     */
    public function removeResponses($id, ResponsesRepository $responsesRepository, EntityManagerInterface $entityManager)
    {
        $responses = $responsesRepository->find($id);

        $entityManager->remove($responses);
        $entityManager->flush();

        $this->addFlash('Success', 'La réponse a bien été supprimée !');

        return $this->render('base/message.html.twig');
    }

}
