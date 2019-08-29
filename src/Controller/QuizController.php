<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\Responses;
use App\Entity\Quiz;
use App\Repository\FamillesRepository;
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
     * @Route("question{id}", name="questions")
     */
    public function questionsController(QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository, $id)
    {
        $responses = $responsesRepository->find($id);
        $questions = $questionsRepository->find($id);
        return $this->render('questions.html.twig',
        [
            'questions' => $questions,
            'responses' => $responses
            ]
        );
    }

    /**
     * @Route("response{id}", name="responses")
     */
    public function responsesController(QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository, $id)
    {
        $questions = $questionsRepository->find($id);
        $responses = $responsesRepository->find($id);
        return $this->render('responses.html.twig',
            [
                'responses' => $responses,
                'questions' => $questions
            ]
        );
    }

    /**
     * @Route("score", name="score")
     */
    public function scoreController(QuestionsRepository $questionsRepository, ResponsesRepository $responsesRepository)
    {
        $questions = $questionsRepository->findAll();
        $responses = $responsesRepository->findAll();
        return $this->render('score.html.twig',
        [
            'responses' => $responses,
            'questions' => $questions
        ]
        );
    }
















}