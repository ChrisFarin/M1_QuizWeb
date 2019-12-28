<?php

namespace App\Areas\User\Controller;

use App\Entity\User;
use App\Entity\Quiz;
use App\Areas\User\Form\CreateQuizFormType;
use App\Areas\User\Security\Authenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class CreateQuizController extends AbstractController
{
    /**
     * @Route("/create", name="app_create")
     */
    public function register(Request $request): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(CreateQuizFormType::class, $quiz);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quiz->setName($form->get('Name')->getData());
            $quiz->setIsVisible($form->get('isVisible')->getData());
            //TODO MODIFIER//
            $quiz->setDate({{ "now"|date("m/d/Y") }});
            //TODO MODIFIER//
            $quiz->setAuthor(new User());
            // Peut être utiliser app.user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quiz);
            $entityManager->flush();

            // Je sais pas vers quoi tu veux rediriger
            //return $this->redirectToRoute(route:'questions');

        }

        return $this->render('Areas/User/quiz/create.html.twig', [
            'CreateQuizForm' => $form->createView()
        ]);
    }

}
