<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([],['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));

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

            return $this->redirectToRoute("app_home");
        }

        return $this->render('pins/edit_pin.html.twig',
            [
                'pin' => $pin,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET","PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function create(Request $request, EntityManagerInterface $em) : Response
    {

        $pin = new Pin;

        $form = $this->createForm(PinType::class, $pin,
        [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute("app_home");
        }

        return $this->render('pins/create.html.twig',
            [
                'form' => $form->createView()
            ]);
    }
}
