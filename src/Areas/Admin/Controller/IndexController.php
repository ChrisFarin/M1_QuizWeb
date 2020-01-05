<?php

namespace App\Areas\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    /**
     * @Route("/Admin/", name="app_admin")
     */
    public function index(?UserInterface $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $this->render('Areas/Admin/index/index.html.twig', ['users' => $users, 'UserListMenu' => true]);
    }

    

    /**
     * @Route("/Admin/deleteUser/{id}", name="app_admin_delete_user")
     */
    public function deleteUser(Request $request, $id)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($id);
        if($user == null) {
            return $this->redirectToRoute('app_index');
          }
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }

     /**
     * @Route("/Admin/upUser/{id}", name="app_admin_up_user")
     */
    /*
    public function upUser(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($id);
        $roles[] = 'ROLE_ADMIN';
        $user -> setRoles($roles);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }
    */
}
