<?php

namespace App\Areas\User\Controller;

use App\Entity\User;
use App\Entity\Quiz;
use App\Entity\Question;
use App\Areas\User\Form\CreateQuizFormType;
use App\Areas\User\Form\CreateQuestionFormType;
use App\Areas\User\Security\Authenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;

class QuizController extends AbstractController
{
    /**
     * @Route("/User/Quiz/create", name="app_create_quiz")
     */
    public function create(Request $request, Security $security)
    {
        $quiz = new Quiz();
        $form = $this->createForm(CreateQuizFormType::class, $quiz);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quiz->setName($form->get('Name')->getData());
            $quiz->setIsVisible($form->get('isVisible')->getData());
            $quiz->setDate(new \DateTime('now'));
            $usr = $security->getUser();
            $quiz->setAuthor($usr);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quiz);
            $entityManager->flush();

            // On redirige vers la création de questions
            $response = $this->forward('App\Areas\User\Controller\QuizController::createQuestion', [
                'id'  => $quiz->getId(),
            ]);
            return $response;

        }

        return $this->render('Areas/User/quiz/create.html.twig', [
            'CreateQuizForm' => $form->createView(),
            'CreateQuizMenu' => true,
        ]);
    }

    /**
     * @Route("/User/Quiz/createQuestion/{id}", name="app_create_question",  methods={"GET"})
     */
    public function createQuestion(Request $request, $id)
    {

        $question = new Question();
        $form = $this->createForm(CreateQuestionFormType::class, $question);
        $form->handleRequest($request);

        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);

        if (!$quiz) {
            throw $this->createNotFoundException(
                'No quiz found for id '.$id
            );
        }


        return $this->render('Areas/User/quiz/createQuestion.html.twig', [
            'quizId'   => $id,
            'quizName' => $quiz->getName(),
            'CreateQuestionForm' => $form->createView(),
            'MyQuizsMenu' => true,
        ]);
    }
}
