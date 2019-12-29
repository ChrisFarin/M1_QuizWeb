<?php

namespace App\Areas\User\Controller;

use App\Entity\User;
use App\Entity\Quiz;
use App\Entity\Question;
use App\Entity\Answer;
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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
          ->add('Question', TextType::class)
          ->add('Answer1', TextType::class)
          ->add('isRightAnswer1', CheckboxType::class)
          ->add('Answer2', TextType::class)
          ->add('isRightAnswer2', CheckboxType::class)
          ->add('Answer3', TextType::class)
          ->add('isRightAnswer3', CheckboxType::class)
          ->add('Answer4', TextType::class)
          ->add('isRightAnswer4', CheckboxType::class)
          ->add('Submit', SubmitType::class)
          ->setMethod('GET')
          ->setAction($this->generateUrl('app_create_question', ['id' => $id, ]))
          ->getForm();
        $form->handleRequest($request);

        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);

        if (!$quiz) {
            throw $this->createNotFoundException(
                'No quiz found for id '.$id
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $data = $form -> getData();


            $question = new Question();
            $question->setQuiz($quiz);
            $question->setEntitled($data['Question']);
            $entityManager->persist($question);




            if ($data['Answer1'] != NULL) {
              $r1 = new Answer();
              $r1->setEntitled($data['Answer1']);
              $r1->setQuestion($question);
              $r1->setIsRightAnswer($data['isRightAnswer1']);
              $entityManager->persist($r1);
            }
            if ($data['Answer2'] != NULL) {
              $r2 = new Answer();
              $r2->setEntitled($data['Answer2']);
              $r2->setQuestion($question);
              $r2->setIsRightAnswer($data['isRightAnswer2']);
              $entityManager->persist($r2);
            }
            if ($data['Answer3'] != NULL) {
              $r3 = new Answer();
              $r3->setEntitled($data['Answer3']);
              $r3->setQuestion($question);
              $r3->setIsRightAnswer($data['isRightAnswer3']);
              $entityManager->persist($r3);
            }
            if ($data['Answer4'] != NULL) {
              $r4= new Answer();
              $r4->setEntitled($data['Answer4']);
              $r4->setQuestion($question);
              $r4->setIsRightAnswer($data['isRightAnswer4']);
              $entityManager->persist($r4);
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_index');
        }

         return $this->render('Areas/User/quiz/createQuestion.html.twig', [
            'quizId'   => $id,
            'quizName' => $quiz->getName(),
            'CreateQuestionForm' => $form->createView(),
            'MyQuizsMenu' => true,
        ]);
    }
}
