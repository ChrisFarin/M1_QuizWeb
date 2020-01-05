<?php

namespace App\Areas\User\Controller;

use App\Entity\Quiz;
use App\Entity\Score;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ReceptionController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $quizs = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->getAllVisible();
        // Pas très propre, devrait être fait dans le repo ..
        foreach(array_keys($quizs) as $key) {
            if (count($quizs[$key]->getQuestions()) == 0) {
                unset($quizs[$key]);
            }
        }


        // Generation de stats pour l'affichage
        $usr = $security->getUser();

        $means = array();
        $bestScoresUser = array();
        $bestScores = array();
        $questionsAmount = array();


        foreach($quizs as $quiz) {
            $scores = $this->getDoctrine()
                ->getRepository(Score::class)
                ->findByQuiz($quiz->getId());
            if ($scores == null) {
                $bestScores[$quiz -> getId()] = '-';
            } else {
                $bestScores[$quiz -> getId()] = $scores[0]->getRightAnswer();
            }
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
                $mean = $total / 1;
            } else {
                $mean = $total / $nb;
            }
            $means[$quiz->getId()] = $mean;
            if ($usr != null) {
                $bestScore = $this->getDoctrine()
                    ->getRepository(Score::class)
                    ->getBestScoreByUserAndQuiz($usr ->getId(), $quiz->getId());
                if ($bestScore == null) {
                    $bestScoresUser[$quiz->getId()] = '-';
                } else {
                    $bestScoresUser[$quiz->getId()] = $bestScore[0]->getRightAnswer();

                }
            } else {
                $bestScoresUser[$quiz->getId()] = '-';
            }
            $questionsAmount[$quiz->getId()] = count($quiz->getQuestions());
        }
    


        
        return $this->render('Areas/User/index.html.twig', ['last_username' => $lastUsername, 'error' => $error,
            'QuizListMenu' => true, 'quizs' => $quizs, 'means' => $means, 'bestScoresUser' => $bestScoresUser, 'bestScores' => $bestScores,
            'questionsAmount' => $questionsAmount ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Areas/User/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
