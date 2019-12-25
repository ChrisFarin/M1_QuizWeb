<?php

namespace App\Areas\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/Admin/index", name="index_admin")
     */
    public function index(?UserInterface $user)
    {
        return $this->render('Areas/Admin/index/index.html.twig', ['name' => $user->getUserName()]);
    }
}
