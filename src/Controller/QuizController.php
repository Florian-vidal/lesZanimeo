<?php

namespace App\Controller;

use Swift_Attachment;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\QuestionsRepository;
use App\Repository\ResponsesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{

    // ROUTE DU QUIZ ---------------------------------------------------------------------------------------------------
    /**
     * @Route("quiz", name="quiz")
     */
    public function quizController()
    {
        return $this->render('quiz.html.twig');
    }


    // ROUTE DES QUESTIONS ---------------------------------------------------------------------------------------------
    /**
     * @Route("question{id}/{goodResponses}", name="questions")
     */
    public function questionsController($goodResponses, QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository, $id)
    {
        // Avec la méthode find du repository, je récupère les questions et les responses dans la BDD qui a l'id qui
        // correspond à la wildcard(id) et je les stockent dans deux nouvelles variables puis je les envoient à la vue
        $responses = $responsesRepository->find($id);
        $questions = $questionsRepository->find($id);

        return $this->render('questions.html.twig',
            [
                'questions' => $questions,
                'responses' => $responses,
                'goodResponses' => $goodResponses
            ]
        );
    }


    // ROUTE DES REPONSES ----------------------------------------------------------------------------------------------
    /**
     * @Route("response{id}/{goodResponses}", name="responses")
     */
    public function responsesController($goodResponses, QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository, $id)
    {
        // Je passe en paramètres goodResponses qui va me permettre d'incrémenter dans ma vue d'une unité à chaque fois
        // que le bouton de bonne réponse est cliqué. Il sera stocké dans mon URL et sera montré à la dernière
        // question pour le score final
        $questions = $questionsRepository->find($id);
        $responses = $responsesRepository->find($id);

        return $this->render('responses.html.twig',
            [
                'responses' => $responses,
                'questions' => $questions,
                'goodResponses' => $goodResponses
            ]
        );
    }


    // ROUTE DU SCORE --------------------------------------------------------------------------------------------------
    /**
     * @Route("/score/{goodResponses}", name="score")
     */
    public function sendMail($goodResponses, EntityManagerInterface $entityManager, Request $request, \Swift_Mailer $mailer, QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository)
    {
        $questions = $questionsRepository->findAll();
        $responses = $responsesRepository->findAll();

        $user = $this->getUser();

        if ($request->isMethod('POST')) {

            // Grâce à la méthode SwiftMailer, un message mail est envoyé à l'utilisateur connecté s'il clique sur le
            // bouton de type submit dans ma vue.
            if ($_POST["submit"]) {
                $message = (new \Swift_Message('Score'))
                    ->setFrom(['ezekielsxm@gmail.com' => 'Les Zaniméo'])
                    ->setTo($user->getEmail());

                // Le contenu du mail dépendra de mon contenu du fichier twig scoremail où je récupère mon score final
                // passé en paramètre plus haut ainsi que le username de l'utilisateur connecté
                $message->setBody(
                    $this->renderView(
                        'scoreMail.html.twig', [
                            'user' => $user,
                            'goodResponses' => $goodResponses
                        ]
                    ),
                    'text/html'
                );

                // Le mail contiend également une image jpg stockée en local
                $message->attach(Swift_Attachment::fromPath('assets/img/main/certificat.jpg') ->setDisposition('inline'));

                $mailer->send($message);
                $this->addFlash('Success', 'Le score a bien été envoyé !');
            }
        }

        return $this->render('score.html.twig',
            [
                'responses' => $responses,
                'questions' => $questions,
                'goodResponses' => $goodResponses,
            ]
        );
    }

}