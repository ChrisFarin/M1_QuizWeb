<?php

namespace App\Areas\User\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/create", name="quiz")
     */
    public function create()
    {
        return $this->render('Areas/User/quiz/create.html.twig', [
            'controller_name' => 'QuizController',
        ]);
    }
}
