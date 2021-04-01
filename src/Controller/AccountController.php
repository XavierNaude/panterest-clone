<?php

namespace App\Controller;

use App\Form\UserFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account", methods={"GET"})
     */
    public function show() : Response
    {
        return $this->render('account/show.html.twig');
    }

    /**
     * @Route("/account/edit", name="app_account_edit", methods={"GET","POST"})
     * @return Response
     */

    public function edit(Request $request, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->addFlash('success','Votre compte a été modifié avec succès');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit.html.twig',
        [
            'form' => $form->createView()
        ]);
    }
}
