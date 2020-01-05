<?php

namespace App\Areas\Admin\Controller;

use App\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class QuizListController extends AbstractController
{
    /**
     * @Route("/Admin/QuizList", name="app_quizList")
     */
    public function quizList(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $quizs = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->findAll();

        return $this->render('Areas/Admin/quizList/quizList.html.twig', ['last_username' => $lastUsername, 'error' => $error,
            'QuizListMenu' => true, 'quizs' => $quizs ]);
    }

    /**
     * @Route("/Admin/Quiz/DeleteQuiz/{id}", name="app_admin_delete_quiz")
     */
    public function deleteQuiz(Request $request, $id)
    {
      $entityManager = $this->getDoctrine()->getManager();
      $quiz = $this->getDoctrine()
         ->getRepository(Quiz::class)
         ->find($id);
      if($quiz == null) {
        return $this->redirectToRoute('app_index');
      }
      $entityManager->remove($quiz);
      $entityManager->flush();
      return $this->redirectToRoute('app_quizList');
    }
}
