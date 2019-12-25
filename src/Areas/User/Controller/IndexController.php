<?php

namespace App\Areas\User\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/User/index", name="index_user")
     */
    public function index(?UserInterface $user)
    {
        return $this->render('Areas/User/index/index.html.twig', ['name' => $user->getUserName()]);
    }
}
