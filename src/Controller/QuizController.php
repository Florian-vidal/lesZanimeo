<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\Questions;
use App\Entity\Responses;
use App\Entity\Quiz;
use App\Repository\FamillesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\QuestionsRepository;
use App\Repository\ResponsesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("quiz", name="quiz")
     */
    public function quizController()
    {
        return $this->render('quiz.html.twig');
    }

    /**
     * @Route("question{id}/{goodResponses}", name="questions")
     */
    public function questionsController($goodResponses, QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository, $id)
    {
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

    /**
     * @Route("response{id}/{goodResponses}", name="responses")
     */
    public function responsesController($goodResponses, QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository, $id)
    {
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

    /**
     * @Route("score/{goodResponses}", name="score")
     */
    /*public function scoreController($goodResponses, QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository)
    {
        $questions = $questionsRepository->findAll();
        $responses = $responsesRepository->findAll();
        return $this->render('score.html.twig',
        [
            'responses' => $responses,
            'questions' => $questions,
            'goodResponses' => $goodResponses

        ]
        );
    }*/

    /**
     * @Route("/score/{goodResponses}", name="score")
     */
    public function sendMail($goodResponses, EntityManagerInterface $entityManager, Request $request, \Swift_Mailer $mailer, QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository)
    {
        $questions = $questionsRepository->findAll();
        $responses = $responsesRepository->findAll();

        $user = $this->getUser();



        if ($request->isMethod('POST')) {

            if ($_POST["submit"]) {
                DD("test ok");
                $message = (new \Swift_Message('Score'))
                    ->setFrom(['ezekielsxm@gmail.com' => 'Les Zaniméo'])
                    ->setTo($user->getEmail());

                $message->setBody(
                    $this->renderView(
                        'scoreMail.html.twig', [
                            'user' => $user,
                            'goodResponses' => $goodResponses
                        ]
                    ),
                    'text/html'
                );

                $mailer->send($message);

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