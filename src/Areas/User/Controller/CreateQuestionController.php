<?php

namespace App\Areas\User\Controller;

use App\Entity\User;
use App\Entity\Quiz;
use App\Entity\Question;
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

class CreateQuestionController extends AbstractController
{
    /**
     * @Route("/create/question", name="app_create_question")
     */
    public function register(Request $request, Quiz $quiz): Response
    {
        $question = new Question();
        $form = $this->createForm(CreateQuestionFormType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $question->setEntitled($form->get('Entitled')->getData());
            $question->setQuiz($quiz->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            // On redirige vers la création de réponses
            //return $this->redirectToRoute(route:'response');

        }

        return $this->render('Areas/User/quiz/create.html.twig', [
            'CreateQuizForm' => $form->createView()
        ]);
    }

}
