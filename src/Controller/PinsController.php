<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     * @param PinRepository $pinRepository
     * @return Response
     */
    public function index(PinRepository $pinRepository, UserRepository $userRepo): Response
    {
        $pins = $pinRepository->findBy([],['createdAt' => 'DESC']);
        $user = $this->getUser();
        return $this->render('pins/index.html.twig', compact('pins','user'));

    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET","PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     * @Security("is_granted('ROLE_USER') && user.isVerified()")
     */

    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepo) : Response
    {
        $pin = new Pin;

        $form = $this->createForm(PinType::class, $pin,
            [
                'method' => 'PUT'
            ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success','Le pin à bien été crée !');

            return $this->redirectToRoute("app_home");
        }

        return $this->render('pins/create.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods={"GET"})
     * @param Pin $pin
     * @return Response
     */
    public function show(Pin $pin) : Response
    {
        return $this->render('pins/show_pin.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET","PUT"})
     * @param Pin $pin
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("PIN_MANAGE",subject="pin")
     */
    public function edit(Pin $pin, Request $request, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(PinType::class, $pin,
        [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->addFlash('success','Le pin à bien été modifié!');

            return $this->redirectToRoute("app_home");
        }

        return $this->render('pins/edit_pin.html.twig',
            [
                'pin' => $pin,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_delete", methods={"DELETE"})
     * @param Request $req
     * @param Pin $pin
     * @param EntityManagerInterface $em
     * @return Response
     * @IsGranted("PIN_MANAGE",subject="pin")
     */
    public function delete(Request $req,Pin $pin, EntityManagerInterface $em) : Response
    {
        if($this->isCsrfTokenValid('pin_deletion_'.$pin->getId(),$req->request->get('csrf_token')))
        {
            $em->remove($pin);
            $em->flush();

            $this->addFlash('info','Le pin a bien été supprimé!');
        }

        return $this->redirectToRoute('app_home');
    }
}
