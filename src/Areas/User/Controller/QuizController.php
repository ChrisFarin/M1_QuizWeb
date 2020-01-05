<?php

namespace App\Areas\User\Controller;

use App\Entity\User;
use App\Entity\Quiz;
use App\Entity\Question;
use App\Entity\Answer;
use App\Entity\Score;
use App\Areas\User\Form\CreateOrEditQuizFormType;
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class QuizController extends AbstractController
{
    /**
     * @Route("/User/Quiz/create", name="app_create_quiz")
     */
    public function create(Request $request, Security $security)
    {
        $quiz = new Quiz();
        $form = $this->createForm(CreateOrEditQuizFormType::class, $quiz);
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

        return $this->render('Areas/User/quiz/createOrEditQuiz.html.twig', [
            'CreateOrEditQuizForm' => $form->createView(),
            'CreateQuizMenu' => true,
        ]);
    }
    /**
     * @Route("/User/Quiz/edit/{id}", name="app_edit_quiz",  methods={"GET"})
     */
    public function edit(Request $request, Security $security, $id)
    {
        $quiz = $this->getDoctrine()
              ->getRepository(Quiz::class)
              ->find($id);

        if (!$quiz) {
          return $this->redirectToRoute('app_index');
        }
        $form = $this->createForm(CreateOrEditQuizFormType::class, $quiz);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quiz->setName($form->get('Name')->getData());
            $quiz->setIsVisible($form->get('isVisible')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quiz);
            $entityManager->flush();

            // On redirige vers les quiz de l'utilisateur
            return $this->redirectToRoute('app_user_quiz');
        }

        return $this->render('Areas/User/quiz/createOrEditQuiz.html.twig', [
            'CreateOrEditQuizForm' => $form->createView(),
            'MyQuizsMenu' => true,
            'isEditing' => true,
            'quizId' => $id,
            'quizName' => $quiz->getName(),

        ]);
    }

    /**
     * @Route("/User/Quiz/createQuestion/{id}", name="app_create_question",  methods={"GET"})
     */
    public function createQuestion(Request $request, $id)
    {

        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
          ->add('Question', TextType::class, ['label' => 'Question*'])
          ->add('Answer1', TextType::class, ['label' => 'Réponse 1*'])
          ->add('Answer2', TextType::class, ['label' => 'Réponse 2*'])
          ->add('Answer3', TextType::class, ['label' => 'Réponse 3', 'required' => false])
          ->add('Answer4', TextType::class, ['label' => 'Réponse 4', 'required' => false])
          ->add('GoodAnswer', ChoiceType::class, ['label' => 'Choisissez la bonne réponse',
            'choices' => [
                'Réponse 1' => 'Réponse 1',
                'Réponse 2' => 'Réponse 2',
                'Réponse 3' => 'Réponse 3',
                'Réponse 4' => 'Réponse 4',
            ],
            'placeholder' => 'Veuillez sélectionner une bonne réponse.',
          ])
          ->add('Submit', SubmitType::class, ['label' => 'Ajouter la question'])
          ->setMethod('GET')
          ->setAction($this->generateUrl('app_create_question', ['id' => $id, ]))
          ->getForm();


        $form->handleRequest($request);

        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);

        if (!$quiz) {
          return $this->redirectToRoute('app_index');
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
              $r1->setIsRightAnswer($data['GoodAnswer'] == 'Réponse 1');
              $entityManager->persist($r1);
            }
            if ($data['Answer2'] != NULL) {
              $r2 = new Answer();
              $r2->setEntitled($data['Answer2']);
              $r2->setQuestion($question);
              $r2->setIsRightAnswer($data['GoodAnswer'] == 'Réponse 2');
              $entityManager->persist($r2);
            }
            if ($data['Answer3'] != NULL) {
              $r3 = new Answer();
              $r3->setEntitled($data['Answer3']);
              $r3->setQuestion($question);
              $r3->setIsRightAnswer($data['GoodAnswer'] == 'Réponse 3');
              $entityManager->persist($r3);
            }
            if ($data['Answer4'] != NULL) {
              $r4= new Answer();
              $r4->setEntitled($data['Answer4']);
              $r4->setQuestion($question);
              $r4->setIsRightAnswer($data['GoodAnswer'] == 'Réponse 4');
              $entityManager->persist($r4);
            }
            $scores = $this->getDoctrine()
            ->getRepository(Score::class)
            ->findByQuiz($id);
            if ($scores != null && (count($scores)) > 0) {
               foreach($scores as $score) {
                 $entityManager->remove($score);
               }
            }
            foreach($quiz-> getQuestions() as $q) {
              $q -> setAnsweredRight(0);
              $q -> setTotalAnswered(0);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_create_question', ['id' => $id]);
        }

         return $this->render('Areas/User/quiz/createOrEditQuestion.html.twig', [
            'quizId'   => $id,
            'quizName' => $quiz->getName(),
            'CreateQuestionForm' => $form->createView(),
            'MyQuizsMenu' => true,
        ]);
    }

    /**
     * @Route("/User/Quiz/editQuestion/{id}", name="app_edit_question",  methods={"GET"})
     */
    public function editQuestion(Request $request, $id)
    {
        $question = $this->getDoctrine()
            ->getRepository(Question::class)
            ->find($id);

        if (!$question) {
          return $this->redirectToRoute('app_index');
        }
        $r1 = $question ->getAnswers()[0];
        $r2 = $question ->getAnswers()[1];
        $r3 = $question ->getAnswers()[2];
        $r4 = $question ->getAnswers()[3];

        $defaultData = ['Question' => $question-> getEntitled(), 'Answer1' => $r1->getEntitled(),
        'Answer2' => $r2->getEntitled(),
        'Answer3' => $r3 == NULL ? '' : $r3->getEntitled(),
        'Answer4' => $r4 == NULL ? '' : $r4->getEntitled(),
        'GoodAnswer' => $r1->getIsRightAnswer() ? 'Réponse 1' : $r2->getIsRightAnswer() ? 'Réponse 2' : $r3 -> getIsRightAnswer() ? 'Réponse 3' : 'Réponse 4', ];
        $form = $this->createFormBuilder($defaultData)
          ->add('Question', TextType::class, ['label' => 'Question*'])
          ->add('Answer1', TextType::class, ['label' => 'Réponse 1*'])
          ->add('Answer2', TextType::class, ['label' => 'Réponse 2*'])
          ->add('Answer3', TextType::class, ['label' => 'Réponse 3', 'required' => false])
          ->add('Answer4', TextType::class, ['label' => 'Réponse 4', 'required' => false])
          ->add('GoodAnswer', ChoiceType::class, ['label' => 'Choisissez la bonne réponse',
            'choices' => [
                'Réponse 1' => 'Réponse 1',
                'Réponse 2' => 'Réponse 2',
                'Réponse 3' => 'Réponse 3',
                'Réponse 4' => 'Réponse 4',
            ],
            'placeholder' => 'Veuillez sélectionner une bonne réponse.',
          ])
          ->add('Submit', SubmitType::class, ['label' => 'Editer la question'])
          ->setMethod('GET')
          ->setAction($this->generateUrl('app_edit_question', ['id' => $id, ]))
          ->getForm();


        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $data = $form -> getData();
            $question->setEntitled($data['Question']);
            $entityManager->persist($question);
            foreach($question->getAnswers() as $answer) {
                $question ->removeAnswer($answer);
            }

            if ($data['Answer1'] != NULL) {
              $r1 = new Answer();
              $r1->setEntitled($data['Answer1']);
              $r1->setQuestion($question);
              $r1->setIsRightAnswer($data['GoodAnswer'] == 'Réponse 1');
              $entityManager->persist($r1);
            }
            if ($data['Answer2'] != NULL) {
              $r2 = new Answer();
              $r2->setEntitled($data['Answer2']);
              $r2->setQuestion($question);
              $r2->setIsRightAnswer($data['GoodAnswer'] == 'Réponse 2');
              $entityManager->persist($r2);
            }
            if ($data['Answer3'] != NULL) {
              $r3 = new Answer();
              $r3->setEntitled($data['Answer3']);
              $r3->setQuestion($question);
              $r3->setIsRightAnswer($data['GoodAnswer'] == 'Réponse 3');
              $entityManager->persist($r3);
            }
            if ($data['Answer4'] != NULL) {
              $r4= new Answer();
              $r4->setEntitled($data['Answer4']);
              $r4->setQuestion($question);
              $r4->setIsRightAnswer($data['GoodAnswer'] == 'Réponse 4');
              $entityManager->persist($r4);
            }
            $scores = $this->getDoctrine()
            ->getRepository(Score::class)
            ->findByQuiz($id);
            if ($scores != null && (count($scores)) > 0) {
              foreach($scores as $score) {
                $entityManager->remove($score);
              }
            }
            foreach($question -> getQuiz() -> getQuestions() as $q) {
              $q -> setAnsweredRight(0);
              $q -> setTotalAnswered(0);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_user_quiz');
        }

         return $this->render('Areas/User/quiz/createOrEditQuestion.html.twig', [
            'quizId'   => $id,
            'CreateQuestionForm' => $form->createView(),
            'MyQuizsMenu' => true,
            'quizName' => $question -> getQuiz() -> getName(),
            'isEditing' => true
        ]);
    }


    /**
     * @Route("/Quiz/doQuiz/{id}&{test}", name="app_do_quiz",  methods={"GET"})
     */
    public function doQuiz(Request $request, $id, $test = false)
    {
      $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);

      if (!$quiz ) {
        return $this->redirectToRoute('app_index');
      }
      $questions = $quiz -> getQuestions();
      if ($questions == null || count ($questions) == 0 || !($quiz ->getIsVisible())) {
        return $this->redirectToRoute('app_index');
      }
      $result = array();
      // Obliger d'envoyer les clés du tableau à partir du serveeur pour pouvoir itérer dessus en JS
      foreach ($questions as $question) {
          foreach ($question -> getAnswers() as $answer) {
            if ($answer-> getIsRightAnswer()) {
                $result[$question->getId()] = $answer->getId();
            }
          }
      }
      return $this->render('Areas/User/quiz/doQuiz.html.twig', [
        'quizId'   => $id,
        'hideNavBar' => true,
        'quiz' => $quiz,
        'result' => $result,
        'isTest' => $test,
    ]);


    }
    /**
     * @Route("/User/Quiz/UserPersonalQuiz", name="app_user_quiz")
     */
    public function userPersonalQuiz(Request $request, Security $security)
    {
      $usr = $security->getUser();
      $quizs = $this->getDoctrine()
      ->getRepository(Quiz::class)
      ->getAllByUser($usr->getId());


      return $this->render('Areas/User/quiz/userQuiz.html.twig',
            ['MyQuizsMenu' => true, 'quizs' => $quizs , ]);
    }

    /**
     * @Route("/User/Quiz/QuizQuestions/{id}", name="app_quiz_question")
     */
    public function quizQuestions(Request $request, Security $security, $id)
    {
      $usr = $security->getUser();
      $quizs = $this->getDoctrine()
      ->getRepository(Quiz::class)
      ->find($id);
      if ($usr->getId() != $quizs->getAuthor()->getId()) {
        return $this->redirectToRoute('app_user_quiz');
      }


      return $this->render('Areas/User/quiz/quizQuestion.html.twig',
            ['MyQuizsMenu' => true, 'quizs' => $quizs , ]);
    }

    /**
     * @Route("/User/Quiz/DeleteQuiz/{id}", name="app_delete_quiz")
     */
    public function deleteQuiz(Request $request, $id)
    {
      $entityManager = $this->getDoctrine()->getManager();
      $quiz = $this->getDoctrine()
         ->getRepository(Quiz::class)
         ->find($id);
      if ($quiz == null) {
        return $this->redirectToRoute('app_index');
      }
      $entityManager->remove($quiz);
      $entityManager->flush();
      return $this->redirectToRoute('app_user_quiz');
    }

    /**
     * @Route("/User/Quiz/deleteQuestion/{id}", name="app_delete_question", methods={"GET"})
     */
    public function deleteQuestion(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $question = $this->getDoctrine()
        ->getRepository(Question::class)
        ->find($id);
        if ($question == null) {
          return $this->redirectToRoute('app_index');

        }

      $quizId = $question ->getQuiz()->getId();
      $scores = $this->getDoctrine()
            ->getRepository(Score::class)
            ->findByQuiz($id);
      if ($scores != null && (count($scores)) > 0) {
        foreach($scores as $score) {
          $entityManager->remove($score);
        }
      }
      foreach($question -> getQuiz() -> getQuestions() as $q) {
        $q -> setAnsweredRight(0);
        $q -> setTotalAnswered(0);
      }
      $entityManager->remove($question);
      $entityManager->flush();
      return $this->redirectToRoute('app_quiz_question', ['id' => $quizId]);
    }

    /**
     * @Route("/Quiz/SaveResult", name="app_save_result",  methods={"POST"})
     */
    public function saveResult(Request $request, Security $security)
    {
        $array = $request->request->get('array');
        $quizId = $request->request->get('quizId');
        $nbGoodAnswer = $request->request->get('nbGoodAnswer');
        $quiz = $this->getDoctrine()
         ->getRepository(Quiz::class)
         ->find($quizId);

        $score = new Score();
        $score->setTotalAnswer(count($quiz->getQuestions()));
        $score->setQuiz($quiz);
        $score->setRightAnswer($nbGoodAnswer);
        $usr = $security->getUser();
        if (!$usr) {
            $score->setPlayer(NULL);
        } else {
            $score->setPlayer($usr);
        }

        foreach ($quiz -> getQuestions() as $question) {
            $question->setTotalAnswered($question->getTotalAnswered()+1);
            $idAnswer = $array[$question->getId()];
            foreach ($question -> getAnswers() as $answer) {
                if ($answer-> getId() == $idAnswer) {
                    if ($answer -> getIsRightAnswer()) {
                        $question->setAnsweredRight($question->getAnsweredRight()+1);
                    }
                }
            }
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($score);
        $entityManager->flush();
        return new JsonResponse(['code' => 200]);
    }

    /**
     * @Route("/User/Quiz/statsQuiz/{id}", name="app_stats_quiz",  methods={"GET"})
     */
    public function statsQuiz(Request $request, Security $security, $id)
    {
        $quiz = $this->getDoctrine()
              ->getRepository(Quiz::class)
              ->find($id);
        if ($quiz == null) {
          return $this->redirectToRoute('app_index');
        }
        $scores = $this->getDoctrine()
                ->getRepository(Score::class)
                ->findByQuiz($id);
        $total = 0;
        $nb = 0;
        foreach($scores as $score) {
          if ($score->getTotalAnswer() == count($quiz->getQuestions())) {
            $nb += 1;
            $total = $total + $score->getRightAnswer();
          }
        }
        $mean = 0;
        if ($nb == 0) {
          $mean = '-';
        } else {
          $mean = $total / $nb;
        }
        $questionsResults = array();
        $questionsEntitled = array();
        $questionsAnswers = array();
        $questionsTotal = array();
        foreach($quiz -> getQuestions() as $question) {
          if ($question->getTotalAnswered() != 0) {
            $percent = ($question->getAnsweredRight() * 100) / $question->getTotalAnswered();
          } else {
            $percent = ($question->getAnsweredRight() * 100);
          }
          array_push($questionsResults, $percent);
          array_push($questionsEntitled, $question->getEntitled());
          array_push($questionsAnswers, $question->getAnsweredRight());
          array_push($questionsTotal, $question->getTotalAnswered());


        }


        return $this->render('Areas/User/quiz/statsQuiz.html.twig', [
            'MyQuizsMenu' => true,
            'quizName' => $quiz->getName(),
            'mean' => $mean,
            'questionsResults' => $questionsResults,
            'questionsEntitled' => $questionsEntitled,
            'questionsAnswers' => $questionsAnswers,
            'questionsTotal' => $questionsTotal
        ]);
    }
}
